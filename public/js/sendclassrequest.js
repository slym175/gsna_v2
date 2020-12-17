jQuery(document).ready(function () {
    jQuery('.btn-gs-classroom').click(function (e) {
        e.preventDefault();

        var self = jQuery(this);
        var classroom_id = self.data('classroom');

        jQuery.ajax({
            type: "POST",
            url: request_data.ajax_url,
            data: {
                action: "register_classroom",
                classroom_id: classroom_id
            },
            dataType: "html",
            beforeSend: function(){
                self.empty().html('<i class="fa fa-spinner fa-spin"></i>')
            },
            success: function(response) {
                //Làm gì đó khi dữ liệu đã được xử lý
                // console.log(response)
                self.empty().html(response)
                jQuery('.gs-popup-classroom .contents .noty').empty().append(response);
                jQuery('.gs-popup-classroom').removeClass('d-none')
                
            },
            error: function( jqXHR, textStatus, errorThrown ){
                //Làm gì đó khi có lỗi xảy ra
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        });
    });

    jQuery('.gs-popup-classroom .contents i.fa.fa-times').click(function(e) {
        e.preventDefault();
        jQuery('.gs-popup-classroom').addClass('d-none')
    })

    // jQuery('#filter_province').select2({
    //     data: jQuery.parseJSON(request_data.local_address),
    //     placeholder: 'Tỉnh/thành phố'
    // })

    // jQuery('#filter_district').select2({
    //     data: jQuery.parseJSON(request_data.local_address)[0].district,
    //     placeholder: 'Quận/huyện'
    // })

    // jQuery('#filter_province').on('change',function(){
    //     var thisval = jQuery(this).val();
    //     var dvls_city = jQuery.parseJSON(request_data.local_address);
    //     var distr = "";
    //     for (x in dvls_city) {
    //         if (dvls_city[x].text == thisval) {
    //             dvls_city[x].selected = true;
    //             distr = dvls_city[x].district;
    //         }
    //     }
    //     jQuery('#filter_district').empty()
    //     jQuery('#filter_district').select2({
    //         data: distr,
    //         allowClear: true
    //     })
    // });

    // jQuery("#filter_subject").select2({
    //     placeholder: 'Môn học',
    //     data: jQuery.parseJSON(request_data.subjects)
    // });

    // jQuery("#filter_caphoc").select2({
    //     data: jQuery.parseJSON(request_data.caphoc),
    //     placeholder: 'Cấp học',
    // });

    // jQuery("#filter_target").select2({
    //     placeholder: 'Đối tượng',
    //     data: jQuery.parseJSON(request_data.targets)
    // });

    // jQuery("#filter_formats").select2({
    //     placeholder: 'Hình thức học',
    //     data: jQuery.parseJSON(request_data.formats)
    // });

    jQuery('.filter-title').click(function (e) {
        e.preventDefault();
        var self = jQuery(this);
        if(self.parents('.filter-parent').hasClass('active')) {
            self.parents('.filter-parent').removeClass('active')
        }else{
            jQuery.each(jQuery('.filter-parent'), function (indexInArray, valueOfElement) { 
                if(jQuery(this).hasClass('active')) {
                    jQuery(this).removeClass('active')
                }
            });
            
            self.parents('.filter-parent').addClass('active')
        }
    })

    $('input[type="checkbox"]').change(function() {
        var text = jQuery(this).parents('.filter-menu-list').data('name');
        var count = jQuery(this).parents('.filter-menu-list').find('input[type="checkbox"]:checked').length
        if(jQuery(this).parents('.filter-parent').hasClass('single')) {
            jQuery(this).parents('.filter-parent').find('input[type="checkbox"]:checked').prop( "checked", false )
            jQuery(this).prop( "checked", true )
            jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Chọn ' + jQuery(this).val())
        } else {
            if(count == 0) {
                jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Chọn ' + text)
            }else{
                jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Đã chọn ' + count +' '+ text)
            }
        }
    });
});

// function submitFormFilter(el) {
//     jQuery.ajax({
//         type: "POST",
//         url: request_data.ajax_url,
//         data: {
//             'action' : "classroom_filter",
//             'filter_province'   : jQuery('#filter_province').val(),
//             'filter_district'   : jQuery('#filter_district').val(),
//             'filter_subject'    : jQuery('#filter_subject').val(),
//             'filter_theme'      : jQuery('#filter_theme').val(),
//             'filter_target'     : jQuery('filter_target').val()
//         },
//         dataType: 'json',
//         success: function (response) {
//             console.log(response);
//         },
//         error: function (error) {
//             console.log(error);
//         }
//     });
// }