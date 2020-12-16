jQuery(document).ready(function () {

    var dvls_city = jQuery.parseJSON(local_array.local_address);
    var provinceSelect = jQuery('#user_train_province');
    var districtSelect = jQuery('#user_train_district');
    var current_province = "";
    var current_district = "";

    if(provinceSelect.attr('value') !== "") {
        for (x in dvls_city) {
            if (dvls_city[x].text == provinceSelect.attr('value')) {
                dvls_city[x].selected = true;
                current_province = dvls_city[x];
            }
        }
    }

    provinceSelect.select2({
        data: dvls_city
    })

    var distr = current_province.district;

    if(districtSelect.attr('value') !== "") {
        for (x in distr) {
            if (distr[x].text == districtSelect.attr('value')) {
                distr[x].selected = true;
                current_district = distr[x];
            }
        }
    }

    districtSelect.select2({
        data: distr,
        allowClear: true
    })

    jQuery(provinceSelect).on('change',function(){
        var thisval = jQuery(this).val();
        for (x in dvls_city) {
            if (dvls_city[x].text == thisval) {
                dvls_city[x].selected = true;
                current_province = dvls_city[x];
                distr = current_province.district;
            }
        }
        districtSelect.empty()
        districtSelect.select2({
            data: distr,
            allowClear: true
        })
    });
});