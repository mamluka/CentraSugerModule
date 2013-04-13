$(function () {
    var root = $('#crmTools');

    root.addClass('crm-dashboard');

    var baseUrl = root.data('base-url');
    var record = root.data('record');

    id = $('input[name=record]').val();

    $.get(baseUrl + "/support/crm-dashboard", {id: id}, function (data) {
        root.append('<div></div>').html(data);
    });

    var status = $('#status').val();

    if ($('#inputMode').length > 0 && status != 'New' && status != 'Assigned') {

        var container = $('<div><iframe></iframe></div>');
        var iframe = container.find('iframe').attr('src', baseUrl + "/crm/notes/log?id=" + id).css('width', '510px').css('height', '640px').css('border', 'none');

        container.dialog({
            width: 550,
            closeOnEscape: false,
            dialogClass: "noclose"
        });

        $.pm.bind('close-dialog', function () {
            container.dialog('close');
            $.msgBox({
                title: "Note saved",
                content: "Your not has been saved, thank you",
                type: "info",
                showButtons: false,
                autoClose: true
            });
        });
    }


})