<?php

use yii\db\Migration;

class m171220_051129_change_auth_assignments_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%auth_assignment}}', 'user_id', $this->integer()->notNull());
        $this->addForeignKey('{{%fk-auth_assignment-user_id}}', '{{%auth_assignment}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk-auth_assignment-user_id}}', '{{%auth_assignment}}');
        $this->alterColumn('{{%auth_assignment}}', 'user_id', $this->string(64)->notNull());
    }
}
