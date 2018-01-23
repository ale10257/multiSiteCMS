<?php

namespace app\tmp;

use Yii;

/**
 * This is the model class for table "chunks".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property string $text
 */
class Chunks extends \yii\db\ActiveRecord
{

    /** @var int */ 
    public $id; 
    
    /** @var string */ 
    public $name; 
    
    /** @var string */ 
    public $alias; 
    
    /** @var string */ 
    public $description; 
    
    /** @var string */ 
    public $text; 
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chunks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['text'], 'string'],
            [['name', 'alias', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alias' => 'Alias',
            'description' => 'Description',
            'text' => 'Text',
        ];
    }

}
