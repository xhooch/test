<?php

namespace console\components;

use common\amqp\AuctionMessage;
use common\amqp\Queue;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer extends AbstractConsumer
{
    protected function processMessage(AMQPMessage $message): void
    {
        $auctionMessage = new AuctionMessage(json_decode($message->body, true));

        //insert to db
        \Yii::$app->db->createCommand()->insert('item_bets',
            [
                'item_id' => $auctionMessage->itemId,
                'user_id' => $auctionMessage->userId,
            ],
        )->execute();

        //calculate magic here
        $betInfo = \Yii::$app->db->createCommand('
                select ib.item_id, ib.user_id, w.balance, i.step_price, w.currency_code from item_bets ib 
                join user_wallets w on w.user_id=ib.user_id and w.currency_code=:currCode
                join auction_items i on i.id=ib.item_id
                where ib.id=:id',
            [
                'id' => $auctionMessage->userId,
                'currCode' => 840
            ]
        )->queryOne();

        \Yii::$app->db->createCommand()->update('user_wallets',
            [
                'balance' => $betInfo['balance'] - $betInfo['step_price'],
            ],
            'user_id=:userId and currency_code=:currCode',
            [
                'userId' => $auctionMessage->userId,
                'currCode' => 840,
            ]
        )->execute();

        \Yii::$app->db->createCommand('
            update `user_wallets` set balance=balance+:stepPrice where user_id=:userId and currency_code=:currCode',
            [
                'stepPrice' => $betInfo['step_price'],
                'userId' => 4,
                'currCode' => $betInfo['currency_code'],
            ]
        )->execute();
    }

    protected function getQueueName(): string
    {
        return Queue::DEFAULT_NAME;
    }
}