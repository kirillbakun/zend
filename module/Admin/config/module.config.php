<?php
    return array(
        'doctrine' => array(
            'driver' => array(
                'Admin_driver' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                    'cache' => 'array',
                    'paths' => array(__DIR__ . '/../src/Admin/Entity')
                ),
                'orm_default' => array(
                    'drivers' => array(
                        'Admin\Entity' =>  'Admin_driver'
                    ),
                ),
            ),
        ),
        'controllers' => array(
            'invokables' => array(
                'Admin\Controller\Index' => 'Admin\Controller\IndexController',
                'Admin\Controller\Article' => 'Admin\Controller\ArticleController',
                'Admin\Controller\Ajax' => 'Admin\Controller\AjaxController',
            ),
        ),
        'router' => array(
            'routes' => array(
                'admin' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/admin',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Admin\Controller',
                            'controller' => 'Index',
                            'action' => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'default' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' => '/[:controller[/:action[/:id]]]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'id' => '[0-9]+',
                                ),
                                'defaults' => array(
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'view_manager' => array(
            'template_path_stack' => array(
                'admin' => __DIR__ .'/../view',
            ),
            'strategies' => array(
                'ViewJsonStrategy',
            ),
        ),
    );