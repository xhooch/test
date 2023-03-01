<?php

namespace common\components;

use common\amqp\MessageInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPConnectionFactory;
use PhpAmqpLib\Message\AMQPMessage;
use yii\base\Component;

class Connection extends Component
{
    private const DELIVERY_PERSISTENT = 1;

    public string $host;

    public int $port;

    public string $user;

    public string $password;

    public string $vhost;

    public string $ioType;

    public bool $isSecure;

    private AbstractConnection $_connection;

    private float $_connectionTimeout = 10.0;

    private float $_commonTimeout = 3.0;

    public function init()
    {
        $config = new AMQPConnectionConfig();
        $config->setHost($this->host);
        $config->setPort($this->port);
        $config->setUser($this->user);
        $config->setPassword($this->password);
        $config->setVhost($this->vhost);
        $config->setIoType($this->ioType);
        $config->setIsSecure($this->isSecure);
        $config->setConnectionTimeout($this->_connectionTimeout);
        $config->setReadTimeout($this->_commonTimeout);
        $config->setWriteTimeout($this->_commonTimeout);
        $config->setChannelRPCTimeout($this->_commonTimeout);

        $this->_connection = AMQPConnectionFactory::create($config);
        parent::init();
    }

    public function channel($channelId = 1): AMQPChannel
    {
        return $this->_connection->channel($channelId);
    }

    public function sendToQueue(MessageInterface $msg, $queue): void
    {
        $amqpMessage = new AMQPMessage($msg->getPayload(), [
            'delivery_mode' => self::DELIVERY_PERSISTENT
        ]);
        $channel = $this->channel();
        $channel->basic_publish($amqpMessage, routing_key: $queue);
    }
}