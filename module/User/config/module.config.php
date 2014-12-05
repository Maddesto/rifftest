<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	/*'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/User/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'application_entities'
                )
            ),
		),
		'authentication' => array(
			'orm_default' => array(
				'object_manager' => 'Doctrine\ORM\EntityManager',
				'identity_class' => 'User\Entity\Users',
				'identity_property' => 'email',
				'credential_property' => 'password',
			),
		),
	),*/
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
			'User\Controller\Admin' => 'User\Controller\AdminController'
        ),
    ),
	'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'login',
                    ),
                ),
            	'may_terminate' => true,
            	'child_routes' => array(
            		'view' => array(
            			'type'    => 'Segment',
            			'options' => array(
            			'route'    => '[:username]',
            				'constraints' => array(
            					'username' => '[a-zA-Z][a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            					'controller'    => 'User\Controller\Index',
            						'action'        => 'view'
            					),
            				),
            				'may_terminate' => true,
            				'child_routes' => array(
            					'edit' => array(
            						'type'    => 'Literal',
            						'options' => array(
            							'route'    => '/edit',
            								'defaults' => array(
            									'controller'    => 'User\Controller\Index',
            									'action'        => 'edit',
            								),
            							),
            						),
            					),
            			),
            	),
            ),
            'register' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/register',
                    'defaults' => array(
                        'controller'    => 'User\Controller\Index',
                        'action'        => 'register',
                    ),
                ),
            ),
        	'confirm' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/confirm',
        			'defaults' => array(
        				'controller'    => 'User\Controller\Index',
        				'action'        => 'confirm',
        			),
        		),
        	),
        	'login' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/login',
        			'defaults' => array(
        				'controller'    => 'User\Controller\Index',
        				'action'        => 'login',
        			),
        		),
        	),
        	'logout' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/logout',
        			'defaults' => array(
        				'controller'    => 'User\Controller\Index',
        				'action'        => 'logout',
        			),
        		),
        	),
        	'admin' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/admin',
        			'defaults' => array(
        				'controller'    => 'User\Controller\Admin',
        				'action'        => 'Login',
        			),
        		),
        		'may_terminate' => true,
        		'child_routes' => array(
					'user_list' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:type]',
							'constraints' => array(
								'type' => '[a-zA-Z]*',
								//'page' => '[0-9]*'
							),
							'defaults' => array(
								//'page' => 1,
								'controller'    => 'User\Controller\Admin',
								'action'        => 'view',
							)
						),
						'may_terminate' => true,
						'child_routes' => array(
							'admin_edit' => array(
								'type'    => 'Segment',
								'options' => array(
									'route'    => '/[:user_name]/edit',
									'constraints' => array(
										'user_name' => '[a-zA-Z][a-zA-Z0-9_-]*',
									),
									'defaults' => array(
										'controller'    => 'User\Controller\Admin',
										'action'        => 'edit',
									)
								)
							),
							'pager' => array(
								'type'    => 'Segment',
								'options' => array(
									'route'    => '/[:page]',
									'constraints' => array(
										'page' => '[0-9]*',
									),
									'defaults' => array(
										'controller'    => 'User\Controller\Admin',
										'action'        => 'view',
									)
								)
							)	
						)
					),
        			'logout' => array(
	        			'type'    => 'Literal',
	        			'options' => array(
	        				'route'    => '/logout',
	        				'defaults' => array(
	        					'controller'    => 'User\Controller\Admin',
	        					'action'        => 'logout',
	        				)
	        			)
        			)
				)
        	)
        )
     ),
	'photos_location' =>  __DIR__ .'/../../..',
	'photos_public_location' =>  '/photos',
	'default_filename' => 'default.png',
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
			'pager'                   => __DIR__ . '/../view/user/admin/pager.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
