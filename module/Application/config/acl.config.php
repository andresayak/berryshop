<?php

return array(
        'resources'=>array(
            'default' => array(
                'resource' => 'default',
                'children' => array(
                    'home' => array(
                        'resource' => 'home',
                        'children' => array(
                            'profile' => array(
                                'resource' => 'profile',
                            ),
                        ),
                    ),
                    'else'    =>  array(
                        'resource'  =>  'else'
                    ),
                    'signup'    =>  array(
                        'resource'  =>  'signup'
                    ),
                    'login' => array(
                        'resource' => 'login',
                    ),
                    'logout' => array(
                        'resource' => 'logout',
                    ),
                    'news' => array(
                        'resource' => 'news',
                    ),
                    'faq' => array(
                        'resource' => 'faq',
                    ),
                    'about' => array(
                        'resource' => 'about',
                    ),
                    'contacts' => array(
                        'resource' => 'contacts',
                    ),
                    'forgot' => array(
                        'resource'  =>  'forgot'
                    ),
                    'forgot_newpass' => array(
                        'resource'  =>  'forgot_newpass'
                    ),
                    'signup_confirm'    =>  array(
                        'resource'  =>  'signup_confirm'
                    )
                ),
            ),
        ),
);
   