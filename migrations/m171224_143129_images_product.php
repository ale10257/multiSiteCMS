<?php

use yii\db\Migration;

class m171224_143129_images_product extends Migration
{
    protected $table = '{{%product_images}}';
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
            'products_id' => $this->integer()->notNull(),
            'alt' => $this->string(),
            'title_link' => $this->string(),
            'sort' => $this->integer(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-orderProduct-images-id', $this->table, 'products_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
