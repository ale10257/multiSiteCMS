<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use app\components\helpers\adminSidebar\AdminSidebarTree;

$categories = AdminSidebarTree::createTree();
$level_start = 0;
$level = $level_start;
$menu = null;

foreach ($categories as $key => $category) {
    switch ($category->depth) {
        case ($category->depth == $level):
            $menu .= Html::endTag('li') . PHP_EOL;
            break;
        case $category->depth > $level:
            if ($level == $level_start) {
                $options = [
                    'class' => 'sidebar-menu tree',
                    'data-widget' => 'tree'
                ];
            } else {
                $options = [
                    'class' => 'treeview-menu',
                ];
            }
            $menu .= Html::beginTag('ul', $options);
            break;
        case $category->depth < $level:
            $menu .= Html::endTag('li') . PHP_EOL;
            for ($i = $level - $category->depth; $i; $i--) {
                $menu .= Html::endTag('ul') . PHP_EOL;
                $menu .= Html::endTag('li') . PHP_EOL;
            }
            break;
    };
    $fa = $category->icon;
    if (!$fa) {
        $fa = 'circle-o';
    }
    $fa = Html::tag('i', '', ['class' => 'fa fa-' . $fa]);
    if (isset($categories[$key + 1]) && $categories[$key + 1]->depth > $category->depth) {
        $menu .= Html::beginTag('li', ['class' => 'treeview']);
        $menu .= Html::a($fa . Html::tag('span', $category->title), '#');
    } else {
        $active = $this->context->id == $category->name ? ['class' => 'active'] : [];
        $menu .= Html::beginTag('li', $active);
        $menu .= Html::a($fa . Html::tag('span', $category->title), ['/admin/' . $category->name]);
    }

    $level = $category->depth;
}

for ($i = $level; $i > $level_start; $i--) {
    $menu .= Html::endTag('li') . PHP_EOL;
    $menu .= Html::endTag('ul') . PHP_EOL;
}

?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?php
        echo $menu;
        if (\yii::$app->user->identity->isRoot()) {
            require 'left_root_menu.php';
        }
        ?>
    </section>
</aside>
