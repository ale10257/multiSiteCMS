<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.17
 * Time: 19:51
 */

namespace app\core\user\forms;


interface UserFormInterface
{
    /**
     * @return array
     */
    public function rules();

    /**
     * @return array
     */
    public function attributeLabels();
}