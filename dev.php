<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9601,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    /*################ MYSQL CONFIG ##################*/
    'MYSQL' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'timeout' => '5',
        'charset' => 'utf8mb4',
        'POOL_MAX_NUM' => '20',
        'POOL_TIME_OUT' => '0.1',
        'username' => 'root',
        'password' => 'root',
        'db' => 'live_cloud',
        'prefix' => 'lc_',
    ],
    /*################ REDIS CONFIG ##################*/
    'REDIS' => [
        'host' => '127.0.0.1',
        'port' => '6379',
        'auth' => '',
        'POOL_MAX_NUM' => '20',
        'POOL_MIN_NUM' => '5',
        'POOL_TIME_OUT' => '0.1',
    ],
];
