<?php

use yii\db\Migration;

class m171220_062247_menu_admin extends Migration
{
    protected $table = '{{%menu_admin}}';
    protected $tableOptions;

    public function safeUp()
    {
        parent::safeUp();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table,
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(64)->notNull(),
                'title' => $this->string(255),
                'icon' => $this->string(255),
                'show_in_sidebar' => $this->boolean(),
                'tree' => $this->integer(),
                'lft' => $this->integer(),
                'rgt' => $this->integer(),
                'depth' => $this->integer(),
            ],
            $this->tableOptions);
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
