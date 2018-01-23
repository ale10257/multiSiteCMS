<?php

use yii\db\Migration;

class m171222_183720_settings extends Migration
{
    protected $table = '{{%settings}}';
    protected $tableOptions;

    public function safeUp()
    {
        parent::safeUp();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'icon' => $this->string(100),
            'value' => $this->string(100),
            'active' => $this->boolean()->defaultValue(1),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull()
        ], $this->tableOptions);

        //$this->addForeignKey('fk-name-id', $this->table, 'this_child_id', '{{%parent_table}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
