<?php

use yii\db\Migration;

class m171224_140545_products extends Migration
{
    protected $table = '{{%products}}';
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
            'metaDescription' => $this->string(),
            'metaTitle' => $this->string(),
            'description' => $this->text(),
            'count' => $this->integer(),
            'price' => $this->float(),
            'old_price' => $this->float(),
            'code' => $this->string(10),
            'active' => $this->boolean(),
            'sort' => $this->integer(),
            'new_prod' => $this->boolean(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->addForeignKey('fk-orderProduct-category-id', $this->table, 'categories_id', '{{%categories}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
