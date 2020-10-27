<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Form;

use Form\Middleware\AuthMiddleware;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            // 'home' => [
            //     'type' => Segment::class,
            //     'options' => [
            //         'route' => '/',
            //         'defaults' => [
            //             'middleware' => AuthMiddleware::class,
            //         ],
            //     ],
            // ],
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'home',
                    ],
                ],
            ],
            'form' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/form[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'user_home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/user',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'home',
                    ],
                ],
            ],
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/user[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
