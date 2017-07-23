<?php

namespace app;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\CommandBus\Handler\CreatePostCommandHandler;
use Blog\Entity\Post;
use Blog\EventBus\Event\PostWasCreatedEvent;
use Blog\Manager\CurrentUserManager;
use Blog\Provider\CommandBusMiddlewareServiceProvider;
use Blog\Provider\CommandBusServiceProvider;
use Blog\Provider\DoctrineCommandsServiceProvider;
use Blog\Provider\DoctrineMigrationCommandsServiceProvider;
use Blog\Provider\EventBusServiceProvider;
use Blog\Provider\FixtureCommandsServiceProvider;
use Blog\Provider\JMSSerializerServiceProvider;
use Blog\Provider\OutputBuilderServiceProvider;
use Blog\Repository\Doctrine\Builder\IdFilteringBuilder;
use Blog\Repository\Doctrine\Builder\IsPublishedFilteringBuilder;
use Blog\Repository\Doctrine\Builder\PaginatingBuilder;
use Blog\Repository\Doctrine\Builder\PostBodyFilteringBuilder;
use Blog\Repository\Doctrine\Builder\PostTitleFilteringBuilder;
use Blog\Repository\Doctrine\Builder\SortingBuilder;
use Blog\Repository\Doctrine\Builder\UserFilteringBuilder;
use Blog\Repository\Doctrine\PostRepository;
use Blog\Repository\Interfaces\CriteriaInterface;
use Blog\Security\JWTAuthenticator;
use Blog\Security\JWTDecoder;
use Blog\Security\UserProvider;
use Blog\Service\CriteriaValidator;
use Blog\Service\SearchEngine\SearchEngine;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\DBAL\Types\Type;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\HttpFoundation\Request;

function services(Application $app)
{
    # user manager
    $app['user_manager'] = function ($app) {
        $userManager = new CurrentUserManager();
        if ($user = $app['user']) {
            $userManager->setUser($user);
        }

        return $userManager;
    };

    # security
    $app['app.jwt_decoder'] = function ($app) {
        return new JWTDecoder($app['parameters']['jwt_secret']);
    };
    $app['app.jwt_authenticator'] = function ($app) {
        return new JWTAuthenticator($app['app.jwt_decoder']);
    };
    $app->register(new SecurityServiceProvider(), [
        'security.firewalls' => [
            'default' => [
                'stateless' => true,
                'pattern' => '^.*$',
                'methods' => [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_DELETE, Request::METHOD_PATCH],
                'guard' => [
                    'authenticators' => ['app.jwt_authenticator']
                ],
                'users' => function () {
                    return new UserProvider();
                }
            ]
        ]
    ]);

    // command bus
    $app->register(new CommandBusServiceProvider());
    $app->register(new CommandBusMiddlewareServiceProvider());
    $app['command_handlers'] = function () {
        return [
            CreatePostCommand::class => 'command_bus_create_post_command_handler'
        ];
    };
    $app['command_bus_create_post_command_handler'] = function ($app) {
        return new CreatePostCommandHandler($app['doctrine_post_repository'], $app['user_manager'], $app['event_bus']);
    };

    // doctrine repository
    $app['doctrine_post_repository'] = function ($app) {
        /** @var PostRepository $repo */
        $repo = $app['orm.em']->getRepository(Post::class);
        $repo->setBuilders(
            [
                new IsPublishedFilteringBuilder(),
                new PostBodyFilteringBuilder(),
                new PostTitleFilteringBuilder(),
                new UserFilteringBuilder(),
                new IdFilteringBuilder(),
                new PaginatingBuilder(),
                new SortingBuilder(),
            ]
        );

        return $repo;
    };

    // event bus
    $app->register(new EventBusServiceProvider());
    $app['event_subscribers'] = function () {
        return [
            PostWasCreatedEvent::class => [],
        ];
    };

    // other
    $app->register(new DoctrineServiceProvider());
    if (!Type::hasType('uuid')) {
        Type::addType('uuid', 'Blog\\Repository\\Doctrine\\Type\\UuidType');
    }
    $app->register(new DoctrineOrmServiceProvider());
    $app->register(new DoctrineMigrationCommandsServiceProvider());
    $app->register(new DoctrineCommandsServiceProvider());
    $app->register(new ValidatorServiceProvider());
    $app->register(new OutputBuilderServiceProvider());
    $app->register(new JMSSerializerServiceProvider());

    $app['criteria_validator'] = function ($app) {
        return new CriteriaValidator($app['validator']);
    };

    // dev environment
    if (in_array($app['environment'], ['DEV', 'TEST'])) {
        $app->register(new FixtureCommandsServiceProvider());
    }

    // search engine
    $app['search_engine'] = function ($app) {
        $searchEngine = new SearchEngine();
        $searchEngine->setRepositoryMap([
            Post::class => $app['doctrine_post_repository'],
        ]);
        $searchEngine->before(function (CriteriaInterface $criteria) use ($app) {
            $app['criteria_validator']->validate($criteria);
        }, 0);

        return $searchEngine;
    };
}
