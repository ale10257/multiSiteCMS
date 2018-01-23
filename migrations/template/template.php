<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */

echo "<?php" . PHP_EOL;
?>

use yii\db\Migration;

class <?= $className ?> extends Migration
{
    protected $table = '{{%name_table}}';
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
        ],
        $this->tableOptions);

        //$this->addForeignKey('fk-name-id', $this->table, 'this_child_id', '{{%parent_table}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        return $this->dropTable($this->table);
    }
}
