<?php

use yii\db\Migration;

class m180102_061130_orders extends Migration
{
    protected $table = '{{%orders}}';
    protected $tableOptions;

    public function safeUp()
    {
        parent::safeUp();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'data' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'all_sum' => $this->integer(),
            'all_total' => $this->integer(),
            'site_constant' => $this->string()->notNull(),
            'ip_address' => $this->string(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-order_user-id', $this->table, 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
