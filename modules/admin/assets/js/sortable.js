$(function () {
    var galleryAdmin = $('.gallery-admin');
    var tableSortable = $('.sortable-table tbody');
    var item, url;
    if (galleryAdmin.length === 1) {
        item = galleryAdmin;
        $(function () {
            $('.del-img').click(function (e) {
                e.preventDefault();
                $.post(this.href);
                $(this).parents('.item-gallery-admin').fadeOut(500);
            });
        });
        url = item.data('sort');
    }
    if (tableSortable.length === 1) {
        item = tableSortable;
        url = tableSortable.parent('.sortable-table').data('url');
    }
    if (item) {
        item.sortable({
            containment: "parent",
            cursor: "move",
            update: updateHandler,
            delay: 200
        });
        function updateHandler(event, ui) {
            var list = {
                'id' : ui.item.data('id'),
                'sort' : ui.item.index() + 1
            };
            $.post(url, {list: JSON.stringify(list)});
        }
    }
});
