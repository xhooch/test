<?php

use console\components\CommonMigration;

/**
 * Class m230301_094306_auction_items
 */
class m230301_094306_auction_items extends CommonMigration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auction_items}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(),
            'step_price' => $this->double(2)->unsigned()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ], $this->tableOptions);

        $this->createTable('{{%item_bets}}', [
            'id' => $this->primaryKey()->unsigned(),
            'item_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('userId-itemId', '{{%item_bets}}', ['user_id', 'item_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auction_items}}');
        $this->dropTable('{{%item_bets}}');
    }
}
