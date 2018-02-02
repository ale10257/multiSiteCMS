$(function () {
    var liSubMenuActive = $('.treeview li.active');
    if (liSubMenuActive.length === 1) {
        liSubMenuActive.parents('.treeview').addClass('menu-open');
        liSubMenuActive.parents('ul.treeview-menu').show(500);
    }

    $('.btn-active').click(function (e) {
        e.preventDefault();
        var button = $(this);
        $.post(this.href, function (data) {
            if (data) {
                button.addClass('hide-btn');
                button.siblings('.btn-active').removeClass('hide-btn');
            } else {
                location.reload();
            }
        });

    });

    $('#check-all').change(function () {
        var checked = $(this).prop('checked');
        $("form input[type=checkbox]").prop('checked', checked);
    });

    $(document).on('click', '.who-is', function () {
        $.post(this.href, function (data) {
            $('#pre-insert').html(data['data']);
        });
    });

    $(function () {
        var form = $('form.form-ctrl-save');
        if (form.length === 1) {
            $(document).on('keydown', function (e) {
                if (e.ctrlKey && e.which === 83) {
                    form.submit();
                    e.preventDefault();
                }
            });
        }
    });
});
