<?php

use yii\db\Migration;

class m180102_061207_order_products extends Migration
{
    protected $table = '{{%order_products}}';
    protected $tableOptions;

    public function safeUp()
    {
        parent::safeUp();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'product_id' => $this->integer(),
            'count' => $this->smallInteger(),
            'image' => $this->string(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-order-id', $this->table, 'order_id', '{{%orders}}', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk-orderProduct-id', $this->table, 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
