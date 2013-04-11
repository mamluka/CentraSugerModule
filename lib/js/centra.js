$(function () {
    var root = $('#crmTools');

    root.addClass('crm-dashboard');

    var baseUrl = root.data('base-url');
    var record = root.data('record');

    id = $('input[name=record]').val();

    $.get(baseUrl + "/support/crm-dashboard", {id: id}, function (data) {
        root.append('<div></div>').html(data);
    });

    if ($('#inputMode').length > 0) {

        var container = $('<div><iframe></iframe></div>');
        var iframe = container.find('iframe').attr('src', baseUrl + "/crm/notes/log?id=" + id).css('width', '510px').css('height', '640px').css('border', 'none');

        container.dialog({
            width: 550,
            closeOnEscape: false,
            open: function (event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog || ui).hide();
            }
        });

        $.pm.bind('close-dialog', function () {
            container.dialog('close');
            location.reload();
        });
    }


})