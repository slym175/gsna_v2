function insertRepeaterRow(el) {
    var table = jQuery(el).parents('#class_schedule_table');
    var row = jQuery(el).parents('tr.form-row');

    row.clone().appendTo(table);
}

function removeRepeaterRow(el) {
    var row = jQuery(el).parents('tr.form-row');

    row.remove()
}

function removeRow(el) {
    var id = jQuery(el).data('row');
    jQuery.ajax({
        type: "POST",
        url: data_array.ajaxurl,
        data: {
            'action': 'remove_registration_classroom',
            'id': id
        },
        dataType: "html",
        success: function (response) {
            window.location.reload();
        }
    });
}

jQuery(document).ready(function () {
    jQuery('.username.column-username img').removeAttr( "srcset" );
    jQuery('.user-profile-picture img').removeAttr( "srcset" );
    jQuery('#gs_status').change(function (e) { 
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: data_array.ajaxurl,
            data: {
                'action': 'change_registration_classroom_status',
                'status': jQuery('#gs_status').val(),
                'id': jQuery('#gs_status').attr('value')
            },
            dataType: "html",
            success: function (response) {

            }
        });
    });
});