<?php

use yii\db\Migration;

class m171220_050927_users_table extends Migration
{
    protected $table = '{{%users}}';
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
                'login' => $this->string(255),
                'first_name' => $this->string(255)->notNull(),
                'last_name' => $this->string(255)->notNull(),
                'role' => $this->string(64),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string(),
                'email' => $this->string()->notNull()->unique(),
                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ],
            $this->tableOptions);

        //$this->addForeignKey('fk-name-id', $this->table, 'this_child_id', '{{%parent_table}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
