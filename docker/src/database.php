<?php

return [
    'base.db.type' => 'mariadb',

    'db.settings.list' => [

        'mariadb' => [
            'mysql:host=db',
            'port=3306',
            'dbname=' . get_env('MYSQL_DATABASE', ''),
            'charset=utf8',
            'user' => get_env('MYSQL_USER', ''),
            'pass' => get_env('MYSQL_PASSWORD', ''),
        ],
    ]
];
