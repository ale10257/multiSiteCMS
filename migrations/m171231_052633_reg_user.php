<?php

use yii\db\Migration;

class m171231_052633_reg_user extends Migration
{
    protected $table = '{{%reg_users}}';
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
            'users_id' => $this->integer(),
            'post_code' => $this->integer(6),
            'region' => $this->string(),
            'city' => $this->string(),
            'address' => $this->string(),
            'phone' => $this->string()->notNull(),
            'billing_info' => $this->text(),
            'site_constant' => $this->string()->notNull(),
        ],
        $this->tableOptions);

        $this->addForeignKey('fk-access_sites-id', $this->table, 'users_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
