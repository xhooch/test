<?php

namespace console\controllers;

use common\amqp\Queue;
use common\components\Connection;
use yii\console\Controller;

class RabbitQueuesController extends Controller
{
    public function actionInit()
    {
        /** @var Connection $rmq */
        $rmq = \Yii::$app->rmq;
        $channel = $rmq->channel();

        $queues = \Yii::$app->params['queues'];
        foreach ($queues as $queue) {
            $queue = new Queue($queue);
            $channel->queue_declare(
                $queue->name,
                $queue->passive,
                $queue->durable,
                $queue->exclusive,
                $queue->auto_delete,
                $queue->nowait,
                $queue->arguments,
                $queue->ticket
            );
        }
    }
}