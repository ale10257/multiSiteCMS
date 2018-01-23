<?php

use yii\db\Migration;

class m171230_100321_images_gallery extends Migration
{
    protected $table = '{{%gallery_images}}';
    protected $tableOptions;

    public function safeUp()
    {
        parent::safeUp();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'galleries_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'alt' => $this->string(),
            'title_link' => $this->string(),
            'sort' => $this->boolean(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-gallery_images-id', $this->table, 'galleries_id', '{{%galleries}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
