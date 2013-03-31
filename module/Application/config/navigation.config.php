<?php

return array(
    'default' => array(
        'home' => array(
            'label' => 'Home',
            'route' => 'home',
            'order' => 0
        )
    ),
    'admin' => array(
        'home' => array(
            'label' => 'Admin panel',
            'route' => 'admin',
            'order' => 0,
            'pages' => array(
                'users' => array(
                    'label' => 'Users',
                    'route' => 'admin_users',
                    'order' => 0,
                )
            )
        )
    ),
    'footer' => array(
        'home' => array(
            'label' => 'Home',
            'route' => 'home',
            'order' => 0
        ),
        'login' => array(
            'label' => 'Login',
            'route' => 'login',
            'order' => 100
        ),
    )
);