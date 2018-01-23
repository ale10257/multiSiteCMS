<?php

namespace app\core\other\validators;

use yii\validators\RegularExpressionValidator;

class AliasValidator extends RegularExpressionValidator
{
    public $pattern = '#^[a-z0-9-_]*$#s';
    public $message = 'ДОпустимы только строчные буквы латинского алфавита, цифры, _, -.';
}
