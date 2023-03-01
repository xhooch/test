<?php

use console\components\CommonMigration;

class m130524_201442_init extends CommonMigration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
