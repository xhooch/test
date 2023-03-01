<?php

namespace console\controllers;

use common\amqp\AuctionMessage;
use common\amqp\Queue;
use common\components\Connection;
use console\components\Consumer;
use yii\console\Controller;

class ConsumerController extends Controller
{
    public int $prefetch = 0;

    public function actionIndex()
    {
        (new Consumer(['qos' => $this->prefetch]))->start();
    }
}