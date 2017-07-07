<?php

namespace Blog\Console\Command;

use Blog\Exception\RuntimeException;
use Doctrine\DBAL\Driver\PDOConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Finder\Finder;

class LoadFixturesCommand extends Command
{
    const FIXTURE_FILE_PATTERN = '*.sql';

    private $dirs;

    public function __construct(array $dirs, $name = null)
    {
        parent::__construct($name);
        $this->dirs = $dirs;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('fixtures:load')
            ->setDescription('Loads fixtures from the directory.')
            ->setHelp(<<<HELP
The <info>fixtures:load</info> command, loads fixtures from the directory.
HELP
            );
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (empty($this->dirs)) {
            throw new \InvalidArgumentException("Fixture dir parameter is required");
        }

        $helper = new QuestionHelper();
        $question = 'WARNING! You are about to execute a database fixtures'
            . ' that could result in data lost.'
            . ' Are you sure you wish to continue? (y/n)';
        $confirmation = new ConfirmationQuestion($question);
        if (!$helper->ask($input, $output, $confirmation)) {
            return;
        }

        $finder = new Finder();
        $files = $finder->files()
            ->in($this->dirs)
            ->name(self::FIXTURE_FILE_PATTERN)
            ->sortByName()
            ->files();

        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            $this->processFile($file->getPathname(), $output);
        }
    }

    protected function processFile($filePath, OutputInterface $output)
    {
        $conn = $this->getHelper('db')->getConnection();

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(
                sprintf("SQL file '<info>%s</info>' does not exist.", $filePath)
            );
        } elseif (!is_readable($filePath)) {
            throw new \InvalidArgumentException(
                sprintf("SQL file '<info>%s</info>' does not have read permissions.", $filePath)
            );
        }

        $output->write(sprintf("Processing file '<info>%s</info>'... ", $filePath));
        $sql = file_get_contents($filePath);

        if ($conn instanceof PDOConnection) {
            // PDO Drivers
            try {
                $lines = 0;

                $stmt = $conn->prepare($sql);
                $stmt->execute();

                do {
                    // Required due to "MySQL has gone away!" issue
                    $stmt->fetch();
                    $stmt->closeCursor();

                    $lines++;
                } while ($stmt->nextRowset());

                $output->write(sprintf('%d statements executed!', $lines) . PHP_EOL);
            } catch (\PDOException $e) {
                $output->write('error!' . PHP_EOL);

                throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
            }
        } else {
            // Non-PDO Drivers (ie. OCI8 driver)
            $stmt = $conn->prepare($sql);
            $rs = $stmt->execute();

            if ($rs) {
                $output->writeln('OK!' . PHP_EOL);
            } else {
                $error = $stmt->errorInfo();

                $output->write('error!' . PHP_EOL);

                throw new RuntimeException($error[2], $error[0]);
            }

            $stmt->closeCursor();
        }
    }
}
