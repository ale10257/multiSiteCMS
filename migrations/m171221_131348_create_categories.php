<?php

use yii\db\Migration;
use app\core\categories\CategoryRepository;

class m171221_131348_create_categories extends Migration
{
    protected $table = '{{%categories}}';
    protected $tableOptions;

    /**
     * @return bool|void
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'icon' => $this->string(100),
            'type_category' => $this->string(32)->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'multiple' => $this->boolean()->defaultValue(0),
            'image' => $this->string(),
            'metaDescription' => $this->string(),
            'metaTitle' => $this->string(),
            'tree' => $this->integer()->notNull()->defaultValue(1),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),

        ], $this->tableOptions);

        //$this->addForeignKey('fk-name-id', $this->table, 'this_child_id', '{{%parent_table}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
