<?php

namespace console\components;

use yii\db\Migration;

class CommonMigration extends Migration
{
    protected ?string $tableOptions;

    public function init()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        parent::init();
    }
}