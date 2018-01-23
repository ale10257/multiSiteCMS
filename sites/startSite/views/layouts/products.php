<?php
/**
 * @var \yii\web\View $this
 * @var \app\core\products\repositories\ProductRepository[] $categories
 */

use yii\helpers\Html;

if ($categories = $this->params['products']) {

    $level_start = 1;
    $level = 2;
    $menu = null;

    foreach ($categories as $key => $category) {

        switch ($category->depth) {
            case ($category->depth == $level):
                $menu .= Html::endTag('li') . PHP_EOL;
                break;
            case $category->depth > $level:
                $class_ul = $level == $level_start ? 'navbar-nav navbar-right nav' : 'dropdown-menu';
                $menu .= Html::beginTag('ul', ['class' => $class_ul]);
                break;
            case $category->depth < $level:
                $menu .= Html::endTag('li') . PHP_EOL;
                for ($i = $level - $category->depth; $i; $i--) {
                    $menu .= Html::endTag('ul') . PHP_EOL;
                    $menu .= Html::endTag('li') . PHP_EOL;
                }
                break;
        };

        if (isset($categories[$key + 1]) && $categories[$key + 1]->depth > $category->depth) {
            $menu .= Html::beginTag('li', ['class' => 'dropdown']);
            $menu .= Html::a($category->name . '<span class="caret"></span>', '#', [
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown'
            ]);
        } else {
            $menu .= Html::beginTag('li');
            $menu .= Html::a($category->name, ['/product/' . $category->alias]);
        }

        $level = $category->depth;
    }

    for ($i = $level; $i > $level_start; $i--) {
        $menu .= Html::endTag('li') . PHP_EOL;
        $menu .= Html::endTag('ul') . PHP_EOL;
    }

    echo $menu;
}
