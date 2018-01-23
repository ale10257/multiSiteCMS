<?php

use yii\db\Migration;

class m171225_181128_article extends Migration
{
    protected $table = '{{%articles}}';
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
            'categories_id' => $this->integer()->notNull(),
            'image' => $this->string(),
            'metaDescription' => $this->string(),
            'metaTitle' => $this->string(),
            'short_text' => $this->text(),
            'text' => $this->text(),
            'active' => $this->boolean(),
            'sort' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-category-id', $this->table, 'categories_id', '{{%categories}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
