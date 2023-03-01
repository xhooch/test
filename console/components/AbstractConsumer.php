<?php

namespace console\components;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPIOWaitException;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use React\EventLoop\Loop;
use React\EventLoop\LoopInterface;
use yii\base\BaseObject;

abstract class AbstractConsumer extends BaseObject
{
    private const WAIT_BASE = 0.01;

    public LoopInterface $loop;

    public int $qos = 2;

    protected AMQPChannel $channel;

    private $_wt;

    private $_idleSince;

    public function init()
    {
        parent::init();

        $this->loop = Loop::get();
        (new Pcntl($this->loop))->on(SIGTERM, [$this, 'onSigTerm']);
    }

    public function start(): void
    {
        $this->channel = \Yii::$app->rmq->channel();
        $this->channel->basic_qos(0, $this->qos, true);
        $this->channel->basic_consume(
            queue: $this->getQueueName(),
            consumer_tag: 'worker',
            callback: [$this, 'processMessageInternal']
        );
        $this->setWaitTimer(self::WAIT_BASE);
        $this->loop->addPeriodicTimer(30, [$this, 'onIdle']);
        $this->loop->run();
    }

    public function stop(): void
    {
        $this->channel->basic_cancel('worker');
        $this->loop->stop();
    }


    public function waitChannel(): void
    {
        try {
            $this->channel->wait(null, true, 0.05);
        } catch (\RuntimeException $e) {
            if ($e instanceof AMQPTimeoutException) {
                // just skip the timeout
                return;
            } else {
                if ($e instanceof AMQPIOWaitException) {
                    // stream_select in AMQPReader::wait returned false, probably because we caught an interrupt signal from OS, skip
                    return;
                } else {
                    throw $e;
                }
            }
        }
    }

    public function setWaitTimer($period): void
    {
        /* drop old timer */
        $this->_wt?->cancel();
        $this->_wt = $this->loop->addPeriodicTimer($period, [$this, 'waitChannel']);
    }

    public function onSigTerm()
    {
        $this->stop();
    }

    public function onIdle()
    {
        if (time() - $this->_idleSince < 30) {
            return;
        }

        \Yii::$app->db->close();
    }

    public function processMessageInternal(AMQPMessage $msg): void
    {
        $this->_idleSince = time();
        $this->processMessage($msg);
    }

    public function ack($msg): void
    {
        $this->channel->basic_ack($msg->delivery_info['delivery_tag']);
    }

    public function nack($msg): void
    {
        $this->channel->basic_reject($msg->delivery_info['delivery_tag'], true);
    }

    public function drop($msg): void
    {
        $this->channel->basic_reject($msg->delivery_info['delivery_tag'], false);
    }

    public function requeue($msg): void
    {
        $this->nack($msg);
    }

    abstract protected function processMessage(AMQPMessage $message): void;

    abstract protected function getQueueName(): string;
}