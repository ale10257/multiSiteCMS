

$menuAdmin = [
'default' => [
'title' => 'Главная',
'icon' => 'dashboard',
'show_in_sidebar' => 1,
],
'order' => [
'title' => 'Заказы',
'icon' => 'shopping-cart',
'show_in_sidebar' => 1,
],
'category' => [
'title' => 'Категории',
'icon' => 'sfolder',
'show_in_sidebar' => 1,
],
'article' => [
'title' => 'Статьи',
'icon' => 'newspaper-o',
'show_in_sidebar' => 1,
],
'product' => [
'title' => 'Продукты',
'icon' => 'product-hunt',
'show_in_sidebar' => 1,
],
'gallery' => [
'title' => 'Галереи',
'icon' => 'file-image-o',
'show_in_sidebar' => 1,
],
'clear-cache' => [
'title' => 'Сбросить cache',
'icon' => '',
'show_in_sidebar' => 0,
],
'setting' => [
'title' => 'Настройки',
'icon' => 'cog',
'show_in_sidebar' => 1,
],
'discount' => [
'title' => 'Скидки',
'icon' => 'tags',
'show_in_sidebar' => 1,
],
'chunk' => [
'title' => 'Чанки',
'icon' => 'code',
'show_in_sidebar' => 1,
],
'reg-user' => [
'title' => 'Клиенты',
'icon' => 'user',
'show_in_sidebar' => 1,
],
];

foreach ($menuAdmin as $key => $menu) {
$form = new MenuAdminForm();
$menuRepository = new MenuAdminRepository();
$form->name = $key;
$form->title = $menu['title'];
$form->icon = $menu['icon'];
$form->show_in_sidebar = $menu['show_in_sidebar'];
$menuRepository->insertValues($form);
$menuRepository->saveItem();
}