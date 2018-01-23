<?php

use yii\db\Migration;

class m171231_060243_access_sites extends Migration
{
    protected $table = '{{%access_sites}}';
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
            'users_id' => $this->integer()->notNull(),
            'site_constant' => $this->string()->notNull(),
        ],
        $this->tableOptions);

        $this->addForeignKey('fk-access_sites_key-id', $this->table, 'users_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
