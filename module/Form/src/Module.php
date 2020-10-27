<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Form;

use Form\Middleware\AuthMiddleware;
use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Psr7Bridge\Psr7Response;
use Laminas\Psr7Bridge\Psr7ServerRequest;
use Laminas\Db\Adapter\Adapter;

class Module implements ConfigProviderInterface
{
    public function getConfig() : array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    // public function onBootstrap($e)
    // {
    //     if ($e->getRequest()->getRequestUri() === '/form/abc') {
    //         header('Location: /form/7');
    //         exit();
    //     }
    // }

    public function getServiceConfig() {
        return [
            'factories' => [
                Model\FormTable::class => function($container) {
                    $tableGateway = $container->get(Model\FormTableGateway::class);
                    return new Model\FormTable($tableGateway);
                },
                Model\FormTableGateway::class => function($container) {
                    $adapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Form);
                    return new TableGateway('forms', $adapter, null, $resultSetPrototype);
                },
                Model\PriorityTable::class => function($container) {
                    $tableGateway = $container->get(Model\PriorityTableGateway::class);
                    return new Model\PriorityTable($tableGateway);
                },
                Model\PriorityTableGateway::class => function($container) {
                    $adapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Priority);
                    return new TableGateway('priorities', $adapter, null, $resultSetPrototype);
                },
                Model\UserTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserTableGateway::class);
                    return new Model\UserTable($tableGateway);
                },
                Model\UserTableGateway::class => function($container) {
                    $adapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User);
                    return new TableGateway('users', $adapter, null, $resultSetPrototype);
                }
            ]
        ];
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                Controller\IndexController::class => function($container) {
                    return new Controller\IndexController(
                        $container->get(Model\FormTable::class),
                        $container->get(Model\PriorityTable::class),
                        $container->get(Model\UserTable::class),
                        $container->get(Adapter::class),
                        new AuthenticationService()
                    );
                },
                Controller\LoginController::class => function($container) {
                    return new Controller\LoginController(
                        new AuthenticationService(),
                        $container->get(Adapter::class),
                        $container->get(Model\UserTable::class)
                    );
                }
            ]
        ];
    }

    # let the framework know about your plugin
    public function getControllerPluginConfig()
    {
        return [
            'aliases' => [
                'authPlugin' => AuthPlugin::class,
            ],
            'factories' => [
                AuthPlugin::class => AuthPluginFactory::class
            ],
        ];
    }
}
