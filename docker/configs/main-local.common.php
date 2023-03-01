<?php

use PhpAmqpLib\Connection\AMQPConnectionConfig;

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=' . getenv('APP_DB_HOST') . ';port='
                . getenv('APP_DB_PORT') .';dbname=' . getenv('APP_DB_NAME'),
            'username' => getenv('APP_DB_USERNAME'),
            'password' => getenv('APP_DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'rmq' => [
            'host' => getenv('APP_RABBIT_HOST'),
            'port' => getenv('APP_RABBIT_PORT'),
            'user' => getenv('APP_RABBIT_USER'),
            'password' => getenv('APP_RABBIT_PASS'),
            'vhost' => getenv('APP_RABBIT_VHOST'),
            'ioType' => AMQPConnectionConfig::IO_TYPE_STREAM,
            'isSecure' => false,
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
    ],
];
