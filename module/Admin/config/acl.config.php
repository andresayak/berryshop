<?php

return array(
    'resources' => array(
        'admin' => array(
            'resource' => 'admin',
            'children' => array(
                'admin-lib' => array(
                    'resource' => 'admin-lib',
                    'children' => array(
                        'admin-lib/sub' => array(
                            'resource' => 'admin-lib/sub',
                            'children' => array(
                            )
                        )
                    )
                ),
                'admin-system' => array(
                    'resource' => 'admin-system',
                    'children' => array(
                        'admin-system/sub' => array(
                            'resource' => 'admin-system/sub',
                            'children' => array(
                            )
                        )
                    )
                )
            ),
        ),
    ),
);
