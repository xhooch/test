<?php

namespace console\controllers;

use common\amqp\AuctionMessage;
use common\amqp\Queue;
use common\components\Connection;
use yii\console\Controller;

class AuctionController extends Controller
{
    private Connection $rmq;

    public function beforeAction($action)
    {
        $this->rmq = \Yii::$app->rmq;
        return parent::beforeAction($action);
    }

    public function actionBet(int $userId, int $itemId)
    {
        $message = new AuctionMessage(
            [
                'userId' => $userId,
                'itemId' => $itemId,
            ]
        );

        $this->rmq->sendToQueue($message, Queue::DEFAULT_NAME);
    }

    public function actionBets(int $userId, int $itemId, int $count)
    {
        for ($i = 0; $i <= $count; $i++) {
            $message = new AuctionMessage(
                [
                    'userId' => $userId,
                    'itemId' => $itemId,
                ]
            );

            $this->rmq->sendToQueue($message, Queue::DEFAULT_NAME);
        }

        unset($message);
    }
}