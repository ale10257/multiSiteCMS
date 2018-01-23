<?php

use yii\db\Migration;

class m171230_184759_chunk extends Migration
{
    protected $table = '{{%chunks}}';
    protected $tableOptions;

    public function safeUp()
    {
        parent::safeUp();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'alias' => $this->string()->notNull()->unique(),
            'description' => $this->string(),
            'text' => $this->text(),
        ], $this->tableOptions);
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
