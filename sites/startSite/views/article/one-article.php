<?php
/**
 * @var \yii\web\View $this
 * @var \app\core\articles\getArticles\DataArticle $article
 */

use app\assets\FancyBoxAsset;

FancyBoxAsset::register($this);

$this->title = $article->metaTitle;

if ($article->metaDescription) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $article->metaDescription
    ]);
}

if ($article->category) {
    $this->params['breadcrumbs'][] = [
        'label' => $article->category->name,
        'url' => ['/articles/' . $article->category->alias],
    ];
}

$this->params['breadcrumbs'][] = $article->title;

?>

<h1><?= $article->title ?></h1>

<?= $article->articleText ?>

<?php if($article->articleGallery) : ?>
<h3>Article gallery</h3>
<ul class="gallery">
    <? foreach($article->articleGallery  as $image) : ?>
    <li>
        <a href="<?= $image->webPath ?>" data-fancybox="gallery">
            <img src="<?= $image->webThumbPath ?>">
        </a>
    </li>
    <? endforeach ?>
</ul>
<?php endif ?>
