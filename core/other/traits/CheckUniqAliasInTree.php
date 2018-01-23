<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.12.17
 * Time: 7:13
 */

namespace app\core\other\traits;

trait CheckUniqAliasInTree
{
    /**
     * @param string $alias
     * @param int $tree
     * @return void
     */
    public function checkUniqAlias(string $alias = null, int $tree = null)
    {
        if ($alias === null && $tree === null) {
            if (!$this->id) {
                throw new \DomainException('Field alias and field id not bee NULL');
            }
            $query = static::find()->where(['alias' => $this->alias, 'tree' => $this->tree])->andWhere(['<>', 'id', $this->id]);
        } else {
            $query = static::find()->where(['alias' => $alias, 'tree' => $tree]);
        }

        if ($query->count()) {
            throw new \DomainException('Поле алиас должно быть уникальным!');
        }
    }
}