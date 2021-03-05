jQuery(document).ready(function () {
    initCheckList('#filter-subject');
    initCheckList('#filter-caphoc');
    initCheckList('#filter-formats');
    getProvinceChecked();

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
                console.log(response)
                self.empty().html('Đăng ký nhận lớp')
                if(response == "0") {
                    var form = jQuery('#login-form-popup .account-login-inner').html()
                    jQuery('.gs-popup-classroom .contents .noty').empty().append(form)
                    jQuery('.gs-popup-classroom .contents .noty').css('font-size', '15px')
                    jQuery('.gs-popup-classroom').removeClass('d-none')
                } else {
                    jQuery('.gs-popup-classroom .contents .noty').empty().append(response);
                    jQuery('.gs-popup-classroom').removeClass('d-none')
                }
                
            },
            error: function( jqXHR, textStatus, errorThrown ){
                //Làm gì đó khi có lỗi xảy ra
                console.log( 'The following error occured: ' + textStatus, errorThrown );
            }
        });
    });

    jQuery('.filter-actions-button.filter-actions__select').click(function (e) { 
        jQuery(this).parents('.filter-menu').find('.filter-menu-list:not(.d-none) input[type="checkbox"]').prop('checked', true)

        var text = jQuery(this).parents('.filter-parent').find('.filter-menu-list').data('name');
        var count = jQuery(this).parents('.filter-parent').find('.filter-menu-list').find('input[type="checkbox"]:checked').length
        jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Đã chọn ' + count +' '+ text)
    });

    jQuery('.filter-actions-button.filter-actions__delete').click(function (e) { 
        jQuery(this).parents('.filter-menu').find('.filter-menu-list:not(.d-none) input[type="checkbox"]').prop('checked', false)

        var text = jQuery(this).parents('.filter-parent').find('.filter-menu-list').data('name');
        jQuery(this).parents('.filter-parent').find('.filter-notice').empty().text('Chọn ' + text)
    });

    jQuery('.gs-popup-classroom .contents i.fa.fa-times').click(function(e) {
        e.preventDefault();
        jQuery('.gs-popup-classroom').addClass('d-none')
    })

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

    jQuery(document).on('click', function(e) {
        var container = jQuery('.filter-parent');
        if (!$(e.target).closest(container).length) {
            container.removeClass('active');
        }
    });

    $('.filter-parent input[type="checkbox"]').change(function() {
        var text = jQuery(this).parents('.filter-menu-list').data('name');
        var count = jQuery(this).parents('.filter-menu-list').find('input[type="checkbox"]:checked').length

        if(jQuery(this).parents('.filter-parent').hasClass('single')) {
            jQuery(this).parents('.filter-parent').find('input[type="checkbox"]:checked').not( jQuery(this) ).prop( "checked", false )
            // if(jQuery(this).prop( ":checked", true)) {
            //     jQuery(this).prop( "checked", false)
            // }else{
            //     jQuery(this).prop( "checked", true)
            // }
            jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Chọn ' + jQuery(this).val())
            var key = jQuery(this).data('key')
            jQuery( "#filter-district .filter-menu-list" ).each(function() {
                if(key == jQuery(this).data('pkey')) {
                    jQuery(this).removeClass( "d-none" );
                }else{
                    jQuery(this).addClass( "d-none" );
                }
            });
        } else {
            if(count <= 0) {
                jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Chọn ' +text)
            }else{
                jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Đã chọn ' + count +' '+ text)
            }
        }
    });
});



jQuery('#filter-district input[type="checkbox"]').change(function() {
    console.log(jQuery(this))
    var text = jQuery(this).parents('.filter-menu-list').data('name');
    var count = jQuery(this).parents('.filter-menu-list').find('input[type="checkbox"]:checked').length
    if(count <= 0) {
        jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Chọn ' + text)
    }else{
        jQuery(this).parents('.filter-parent').find('.filter-notice').empty().html('Đã chọn ' + count +' '+ text)
    }
});

function initCheckList(id) {
    var count = jQuery(id + ' input[type="checkbox"]:checked').length
    if(count <= 0) {
        var text = jQuery(id + ' input[type="checkbox"]:checked').parents('.filter-menu-list').data('name');
        jQuery(id + ' input[type="checkbox"]:checked').parents('.filter-parent').find('.filter-notice').empty().text('Chọn ' + text)
    }else{
        var text = jQuery(id + ' input[type="checkbox"]:checked').parents('.filter-menu-list').data('name');
        jQuery(id + ' input[type="checkbox"]:checked').parents('.filter-parent').find('.filter-notice').empty().text('Đã chọn ' + count +' '+ text)
    }

    if(jQuery(id + ' input[type="checkbox"]:checked').length == jQuery(id + ' input[type="checkbox"]').length) {
        jQuery(id + ' input[type="checkbox"]').parents('.filter-parent').find('.filter-actions-button.filter-actions__select').empty().text('Xóa tất cả')
    }
}

function getProvinceChecked() {
    var filter_province = jQuery('#filter-province input[type="checkbox"]:checked')

    if(filter_province.length > 0) {
        var text = filter_province.parents('.filter-menu-list').data('name');
        var count = filter_province.parents('.filter-menu-list').find('input[type="checkbox"]:checked').length

        if(filter_province.parents('.filter-parent').hasClass('single')) {
            filter_province.parents('.filter-parent').find('input[type="checkbox"]:checked').prop( "checked", false )
            filter_province.prop( "checked", true )
            filter_province.parents('.filter-parent').find('.filter-notice').empty().html('Chọn ' + filter_province.val())
            var key = filter_province.data('key')
            jQuery( "#filter-district .filter-menu-list" ).each(function() {
                if(key == jQuery(this).data('pkey')) {
                    jQuery(this).removeClass( "d-none" );
                    initCheckList('#filter-district');
                }else{
                    jQuery(this).addClass( "d-none" );
                }
            });
        } else {
            if(count <= 0) {
                filter_province.parents('.filter-parent').find('.filter-notice').empty().html('Chọn ' +text)
            }else{
                filter_province.parents('.filter-parent').find('.filter-notice').empty().html('Đã chọn ' + count +' '+ text)
            }
        }
    }
}