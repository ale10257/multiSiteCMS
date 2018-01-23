<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.12.17
 * Time: 12:54
 */

namespace app\core\other\activeQuery;

use creocoder\nestedsets\NestedSetsQueryBehavior;

class MenuQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}
