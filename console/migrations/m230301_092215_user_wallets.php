<?php

use console\components\CommonMigration;

/**
 * Class m230301_092215_user_wallets
 */
class m230301_092215_user_wallets extends CommonMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currencies}}', [
            'iso_code_int' => $this->primaryKey(3)->unsigned()->notNull(),
            'iso_code_string' => $this->string(3)->unique(),
            'name' => $this->string(50)
        ], $this->tableOptions);

        $this->createTable('{{%user_wallets}}', [
            'user_id' => $this->integer()->unsigned()->notNull(),
            'currency_code' => $this->smallInteger(3)->unsigned()->notNull(),
            'balance' => $this->double(2)->notNull(),
            'created_at' => $this->timestamp()->notNull(),
        ], $this->tableOptions);

        $this->addPrimaryKey('userId-currCode', '{{%user_wallets}}', ['user_id', 'currency_code']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currencies}}');
        $this->dropTable('{{%user_wallets}}');
    }
}
