<?php

use yii\db\Migration;

class m171230_095626_galleries extends Migration
{
    protected $table = '{{%galleries}}';
    protected $tableOptions;

    /**
     * @return bool|void
     */
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
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull()
        ], $this->tableOptions);
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
