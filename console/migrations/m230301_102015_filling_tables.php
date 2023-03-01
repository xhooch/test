<?php

use common\generators\GenerateAuthKey;
use common\generators\GenerateResetToken;
use common\generators\GeneratePasswordHash;
use common\generators\StringFactory;
use yii\db\Migration;

/**
 * Class m230301_102015_filling_tables
 */
class m230301_102015_filling_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%user}}',
            ['username', 'email', 'auth_key', 'password_hash', 'password_reset_token'],
            [
                [
                    'user0',
                    'user0@gmail.com',
                    StringFactory::create(GenerateAuthKey::class)->generate('user0'),
                    StringFactory::create(GeneratePasswordHash::class)->generate('user0'),
                    StringFactory::create(GenerateResetToken::class)->generate('user0'),
                ],
                [
                    'user1',
                    'user1@gmail.com',
                    StringFactory::create(GenerateAuthKey::class)->generate('user1'),
                    StringFactory::create(GeneratePasswordHash::class)->generate('user1'),
                    StringFactory::create(GenerateResetToken::class)->generate('user1'),
                ],
                [
                    'user2',
                    'user2@gmail.com',
                    StringFactory::create(GenerateAuthKey::class)->generate('user2'),
                    StringFactory::create(GeneratePasswordHash::class)->generate('user2'),
                    StringFactory::create(GenerateResetToken::class)->generate('user2'),
                ],
            ]
        );

        $this->batchInsert('{{%currencies}}',
            ['iso_code_int', 'iso_code_string', 'name'],
            [
                [840, 'USD', 'USA dollar'],
                [826, 'GBP', 'Pound sterling'],
                [810, 'RUR', 'Russian ruble'],
            ],
        );

        $this->batchInsert('{{%user_wallets}}',
            ['user_id', 'currency_code', 'balance'],
            [
                [1, 840, 1000.50],
                [1, 826, 100.00],
                [2, 840, 160.00],
                [2, 826, 160.00],
                [3, 840, 18803.00],
                [3, 810, 18803.00],
                [4, 840, 0],
                [4, 826, 0],
                [4, 810, 0],
            ],
        );

        $this->batchInsert('{{%auction_items}}',
            ['name', 'step_price',],
            [
                ['some item 1', 10.50],
                ['some item 2', 5.00],
                ['some item 3', 11.77],
            ],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%user}}');
        $this->truncateTable('{{%currencies}}');
        $this->truncateTable('{{%user_wallets}}');
        $this->truncateTable('{{%auction_items}}');
    }
}
