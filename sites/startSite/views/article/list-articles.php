<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $category \app\core\categories\CategoryRepository
 */

use yii\widgets\ListView;

$this->title = $category->metaTitle ? : $category->name;

if ($category->metaDescription) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $category->metaDescription
    ]);
}

$this->params['breadcrumbs'][] = $category->name;

?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['id' => 'product-list'],
    'layout' => '{summary} {items} {pager}',
    'summaryOptions' => ['class' => 'top-data pull-left'],
    'itemView' => '_view_article_list'
])
?>