<?php

return array(
    'acl' => array(
        'roles' => array(
            'guest'   => null,
            'user'  => 'guest',
            'admin'  => 'user'
        ),
        'resources' => array(
            'allow' => array(
                'Application\Controller\Index' => array(
                    'index'   => 'guest'
                ),
                'Application\Controller\Auth'   =>  array(
                    '_all'   => 'guest'
                ),
                'Application\Controller\Admin' => array(
                    '_all'   => 'admin'
                ),
                'Application\Controller\Admin\Users' => array(
                    '_all'   => 'admin'
                )
            )
        )
    )
);
   