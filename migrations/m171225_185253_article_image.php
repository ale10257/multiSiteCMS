<?php

use yii\db\Migration;

class m171225_185253_article_image extends Migration
{
    protected $table = '{{%article_images}}';
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
            'articles_id' => $this->integer()->notNull(),
            'alt' => $this->string(),
            'title_link' => $this->string(),
            'sort' => $this->integer(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-article-images-id', $this->table, 'articles_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
