<?php

use yii\db\Migration;

class m171230_140609_discounts extends Migration
{
    protected $table = '{{%discounts}}';
    protected $tableOptions;

    public function safeUp()
    {
        parent::safeUp();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'start_sum' => $this->integer(),
            'percent' => $this->smallInteger(),
            'site_constant' => $this->string(100),
        ], $this->tableOptions);
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
