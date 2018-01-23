$(function () {
    var galleryAdmin = $('.gallery-admin');
    var tableSortable = $('.sortable-table tbody');
    var item, itemParent, url;
    if (galleryAdmin.length === 1) {
        item = galleryAdmin;
        $(function () {
            $('.del-img').click(function (e) {
                e.preventDefault();
                $.post(this.href);
                $(this).parents('.item-gallery-admin').fadeOut(500);
            });
        });
        itemParent = '.gallery-admin .item-gallery-admin-wrap';
        url = item.data('sort');
    }
    if (tableSortable.length === 1) {
        item = tableSortable;
        itemParent = '.sortable-tr';
        url = tableSortable.parent('.sortable-table').data('url');
    }
    if (item) {
        item.sortable({
            containment: "parent",
            cursor: "move",
            update: updateHandler,
            delay: 200
        });
        function updateHandler() {
            var list = [];
            var i = 1;
            $(itemParent).each(function () {
                list.push({
                    sort: i++,
                    id: $(this).attr('data-id')
                });
            });
            $.post(url, {list: JSON.stringify(list)});
        }
    }
});
