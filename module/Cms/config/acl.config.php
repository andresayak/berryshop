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
                'Cms\Controller\Index' => array(
                    '_all'   => 'guest'
                )
            )
        )
    )
);
   