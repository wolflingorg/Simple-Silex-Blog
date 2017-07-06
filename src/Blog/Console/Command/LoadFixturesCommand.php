<?php

namespace Blog\Console\Command;

use Doctrine\DBAL\Driver\PDOConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{
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

        foreach ($this->dirs as $dir) {
            $all_files = new \IteratorIterator(new \DirectoryIterator($dir));
            $sql_files = new \RegexIterator($all_files, '/\.sql$/');

            foreach ($sql_files as $file) {
                if ($file->isFile()) {
                    $this->processFile($file->getPathname(), $output);
                }
            }
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

                throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
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

                throw new \RuntimeException($error[2], $error[0]);
            }

            $stmt->closeCursor();
        }
    }
}
