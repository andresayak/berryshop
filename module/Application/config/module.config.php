<?php

return array(
    'navigation' => include __DIR__ . '/navigation.config.php',
    'di' => array(
        'instance' =>array(
            'Application\Service\Acl' => array(
                'parameters' => array(
                    'config' => include __DIR__ . '/acl.config.php'
                )
            )
        )
    ),
    'router' => array(
        'routes' => include __DIR__ . '/route.config.php'
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'  => 'Application\Controller\IndexController',
            'Application\Controller\Auth'   => 'Application\Controller\AuthController',
            'Application\Controller\Admin'   => 'Application\Controller\AdminController',
            'Application\Controller\Admin\Users'   => 'Application\Controller\Admin_UsersController'
        ),
    ),
     'service_manager' => array(
        'factories' => array(
            'Zend\Session\SessionManager' => function ($sm) {
                $config = $sm->get('config');
                if (isset($config['session'])) {
                    $session = $config['session'];

                    $sessionConfig = null;
                    if (isset($session['config'])) {
                        $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                        $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                        $sessionConfig = new $class();
                        $sessionConfig->setOptions($options);
                    }

                    $sessionStorage = null;
                    if (isset($session['storage'])) {
                        $class = $session['storage'];
                        $sessionStorage = new $class();
                    }

                    $sessionSaveHandler = null;
                    if (isset($session['save_handler'])) {
                        $sessionSaveHandler = new \Zend\Session\SaveHandler\Cache($sm->get($session['save_handler']));
                    }

                    $sessionManager = new \Zend\Session\SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                    if (isset($session['validators'])) {
                        $chain = $sessionManager->getValidatorChain();
                        foreach ($session['validators'] as $validator) {
                            $validator = new $validator();
                            $chain->attach('session.validate', array($validator, 'isValid'));
                        }
                    }
                } else {
                    $sessionManager = new \Zend\Session\SessionManager();
                }
                \Zend\Session\Container::setDefaultManager($sessionManager);
                return $sessionManager;
            },
            'Cache\Redis' => function($sm) {
                $config = $sm->get('Config');
                return Zend\Cache\StorageFactory::factory(array(
                    'adapter' => array(
                        'name' => '\Ap\Cache\Storage\Adapter\Redis',
                        'options' => $config['redis']
                     ),
                     'plugins' => array(
                        'IgnoreUserAbort' => array(
                            'exitOnAbort' => true
                         ),
                     )
                ));
            },
            'Mailer' => function($sm) {
                $config = $sm->get('Config');
                $mailer = new Ap\Service\Mailer($config['mailer']);
                $mailer->setRenderer($sm->get('ViewRenderer'));
                return $mailer;
            },
            'Application\Service\ErrorHandling' => function($sm) {
                return new \Application\Service\ErrorHandling($sm->get('Zend\Log'));
            },
            'Zend\Log' => function ($sm) {
                $config = $sm->get('config');
                $date = date('Y-m-d');
                $logger = new \Zend\Log\Logger;
                if(ERROR_MAILTO){
                    $mail = new \Zend\Mail\Message();
                    $mail->setFrom($config['mailer']['site_email'])
                        ->addTo(ERROR_MAILTO)
                        ->setSubject('Apocalypse critical '.$date.' from server '.SERVER_ID);
                    $writer = new \Zend\Log\Writer\Mail($mail, new \Zend\Mail\Transport\Sendmail());
                    $logger->addWriter($writer);
                }
                $writer = new \Zend\Log\Writer\Stream(__DIR__.'/../../../data/logs/error_' . $date . '.txt');
                $writer->addFilter(new Zend\Log\Filter\Priority(\Zend\Log\Logger::ERR));
                $logger->addWriter($writer);
                return $logger;
                
            },
            'Transaction' => function($sm) {
                return new \Ap\Transaction($sm);
            },
            'navigation' => new Application\Service\NavigationFactory(),
            'navigation/category' => new Application\Service\NavigationFactory\Category(),
            'navigation/footer' => new Application\Service\NavigationFactory('footer'),
            'navigation/admin' => new Application\Service\NavigationFactory('admin'),
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Auth_Service' => function($sm){
                $service = new Application\Service\Auth($sm->get('User_Table'));
                $service->setAcl($sm->get('Application\Service\Acl'));
                return $service;
             },
             'User_Table' =>  function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $table = new Application\Model\User\Table($dbAdapter);
                return $table;
            },
            'Cache\Memcached' => function($sm) {
                $config = $sm->get('Config');
                $cache = Zend\Cache\StorageFactory::factory(array(
                    'adapter' => 'Memcached',
                    'plugins' => array(
                        'exception_handler' => array('throw_exceptions' => false),
                        'serializer'
                    )
                ));
                $cache->setOptions($config['memcached']);
                return $cache;
            },
        ),
        
    ),
    'view_helpers' => array(
        'factories' => array(
            'flashMessage' => function($sm) {
                $flashMessenger = $sm->getServiceLocator()
                    ->get('ControllerPluginManager')
                    ->get('flashmessenger');                                   
                 $message = new Application\View\Helper\FlashMessages();
                 $message->setFlashMessenger($flashMessenger);
                 return $message;
            },
            'auth'  =>  function($sm) {
                $service = $sm->getServiceLocator()->get('Auth_Service');
                $helper = new Application\View\Helper\Auth();
                $helper->setAuthService($service);
                return $helper;
            },
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
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
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),
);
