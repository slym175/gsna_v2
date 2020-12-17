<?php
// function display_classroom_settings() {
//     $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
//     if(isset($_GET['error_message'])){
//         add_action('admin_notices', 'gsnaSettingsMessages');
//         do_action( 'admin_notices', $_GET['error_message'] );
//     }
//     require_once GS_PLUGIN_DIR . '/admin/partials/classroom-setting-admin-display.php';
// }

function gsnaSettingsMessages($error_message){
    switch ($error_message) {
        case '1':
            $message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', GS_TEXTDOMAIN );                 
            $err_code = esc_attr( 'plugin_name_example_setting' );                 
            $setting_field = 'plugin_name_example_setting';                 
            break;
    }
    $type = 'error';
    add_settings_error(
        $setting_field,
        $err_code,
        $message,
        $type
    );
}

function display_classroom_managements() {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
    switch($action) {
        case 'edit':
            require_once GS_PLUGIN_DIR . '/admin/partials/classroom-management/edit.php';
            break;
        default:
            require_once GS_PLUGIN_DIR . '/admin/partials/classroom-managements-admin-display.php';
    }
}

function get_classroom_price()
{
    echo number_format(intval(get_post_meta( get_the_ID(  ), 'class_price', true )), 0, ",", ".")." vnđ/buổi";
}

function get_classroom_fee()
{
    echo number_format(intval(get_post_meta( get_the_ID(  ), 'class_fee', true )), 0, ",", ".")." vnđ";
}

function get_registration_classroom_count($classroom)
{
    global $wpdb;
    $table_name = $wpdb->prefix."gs_user_classrooms";

    $wpdb->get_results(" SELECT * FROM " . $table_name . " WHERE classroom_id = '" . $classroom . "'"); 

    return $wpdb->num_rows;
}

function get_classroom_code($post_id)
{
    echo "CR-" . $post_id;
}

function get_local_checkout_json()
{
    $local = [];
    $cities = array(
        'AN-GIANG'        => array(
            'Huyện An Phú',
            'Huyện Châu Phú',
            'Huyện Châu Thành',
            'Huyện Chợ Mới',
            'Huyện Phú Tân',
            'Huyện Thoại Sơn',
            'Huyện Tịnh Biên',
            'Huyện Tri Tôn',
            'Thành phố Châu Đốc',
            'Thành phố Long Xuyên',
            'Thị xã Tân Châu'
        ),
        'BA-RIA-VUNG-TAU' => array(
            'Huyện Châu Đức',
            'Huyện Côn Đảo',
            'Huyện Đất Đỏ',
            'Huyện Long Điền',
            'Huyện Tân Thành',
            'Huyện Xuyên Mộc',
            'Thành phố Bà Rịa',
            'Thành phố Vũng Tàu'
        ),
        'BAC-LIEU'        => array(
            'Huyện Đông Hải',
            'Huyện Hoà Bình',
            'Huyện Hồng Dân',
            'Huyện Phước Long',
            'Huyện Vĩnh Lợi',
            'Thành phố Bạc Liêu',
            'Thị xã Giá Rai'
        ),
        'BAC-KAN'         => array(
            'Huyện Ba Bể',
            'Huyện Bạch Thông',
            'Huyện Chợ Đồn',
            'Huyện Chợ Mới',
            'Huyện Na Rì',
            'Huyện Ngân Sơn',
            'Huyện Pác Nặm',
            'Thành Phố Bắc Kạn',
        ),
        'BAC-GIANG'       => array(
            'Huyện Hiệp Hòa',
            'Huyện Lạng Giang',
            'Huyện Lục Nam',
            'Huyện Lục Ngạn',
            'Huyện Sơn Động',
            'Huyện Tân Yên',
            'Huyện Việt Yên',
            'Huyện Yên Dũng',
            'Huyện Yên Thế',
            'Thành phố Bắc Giang'
        ),
        'BAC-NINH'        => array(
            'Huyện Gia Bình',
            'Huyện Lương Tài',
            'Huyện Quế Võ',
            'Huyện Thuận Thành',
            'Huyện Tiên Du',
            'Huyện Yên Phong',
            'Thành phố Bắc Ninh',
            'Thị xã Từ Sơn'
        ),
        'BEN-TRE'         => array(
            'Huyện Ba Tri',
            'Huyện Bình Đại',
            'Huyện Châu Thành',
            'Huyện Chợ Lách',
            'Huyện Giồng Trôm',
            'Huyện Mỏ Cày Bắc',
            'Huyện Mỏ Cày Nam',
            'Huyện Thạnh Phú',
            'Thành phố Bến Tre'
        ),
        'BINH-DUONG'      => array(
            'Huyện Bắc Tân Uyên',
            'Huyện Bàu Bàng',
            'Huyện Dầu Tiếng',
            'Huyện Phú Giáo',
            'Thành phố Thủ Dầu Một',
            'Thị xã Bến Cát',
            'Thị xã Dĩ An',
            'Thị xã Tân Uyên',
            'Thị xã Thuận An'
        ),
        'BINH-DINH'       => array(
            'Huyện An Lão',
            'Huyện Hoài Ân',
            'Huyện Hoài Nhơn',
            'Huyện Phù Cát',
            'Huyện Phù Mỹ',
            'Huyện Tây Sơn',
            'Huyện Tuy Phước',
            'Huyện Vân Canh',
            'Huyện Vĩnh Thạnh',
            'Thành phố Qui Nhơn',
            'Thị xã An Nhơn'
        ),
        'BINH-PHUOC'      => array(
            'Huyện Bù Đăng',
            'Huyện Bù Đốp',
            'Huyện Bù Gia Mập',
            'Huyện Chơn Thành',
            'Huyện Đồng Phú',
            'Huyện Hớn Quản',
            'Huyện Lộc Ninh',
            'Huyện Phú Riềng',
            'Thị xã Bình Long',
            'Thị xã Đồng Xoài',
            'Thị xã Phước Long'
        ),
        'BINH-THUAN'      => array(
            'Huyện Bắc Bình',
            'Huyện Đức Linh',
            'Huyện Hàm Tân',
            'Huyện Hàm Thuận Bắc',
            'Huyện Hàm Thuận Nam',
            'Huyện Phú Quí',
            'Huyện Tánh Linh',
            'Huyện Tuy Phong',
            'Thành phố Phan Thiết',
            'Thị xã La Gi'
        ),
        'CA-MAU'          => array(
            'Huyện Cái Nước',
            'Huyện Đầm Dơi',
            'Huyện Năm Căn',
            'Huyện Ngọc Hiển',
            'Huyện Phú Tân',
            'Huyện Thới Bình',
            'Huyện Trần Văn Thời',
            'Huyện U Minh',
            'Thành phố Cà Mau'
        ),
        'CAO-BANG'        => array(
            'Huyện Bảo Lạc',
            'Huyện Bảo Lâm',
            'Huyện Hạ Lang',
            'Huyện Hà Quảng',
            'Huyện Hoà An',
            'Huyện Nguyên Bình',
            'Huyện Phục Hoà',
            'Huyện Quảng Uyên',
            'Huyện Thạch An',
            'Huyện Thông Nông',
            'Huyện Trà Lĩnh',
            'Huyện Trùng Khánh',
            'Thành phố Cao Bằng'
        ),
        'CAN-THO'         => array(
            'Huyện Cờ Đỏ',
            'Huyện Phong Điền',
            'Huyện Thới Lai',
            'Huyện Vĩnh Thạnh',
            'Quận Bình Thuỷ',
            'Quận Cái Răng',
            'Quận Ninh Kiều',
            'Quận Ô Môn',
            'Quận Thốt Nốt',
        ),
        'DA-NANG'         => array(
            'Huyện Hòa Vang',
            'Huyện Hoàng Sa',
            'Quận Cẩm Lệ',
            'Quận Hải Châu',
            'Quận Liên Chiểu',
            'Quận Ngũ Hành Sơn',
            'Quận Sơn Trà',
            'Quận Thanh Khê',
        ),
        'DAK-LAK'         => array(
            'Huyện Buôn Đôn',
            'Huyện Cư Kuin',
            'Huyện Cư M gar',
            'Huyện Ea H leo',
            'Huyện Ea Kar',
            'Huyện Ea Súp',
            'Huyện Krông A Na',
            'Huyện Krông Bông',
            'Huyện Krông Búk',
            'Huyện Krông Năng',
            'Huyện Krông Pắc',
            'Huyện Lắk',
            'Huyện M Đrắk',
            'Thành phố Buôn Ma Thuột',
            'Thị Xã Buôn Hồ'
        ),
        'DAK-NONG'        => array(
            'Huyện Cư Jút',
            'Huyện Đăk Glong',
            'Huyện Đắk Mil',
            'Huyện Đắk R Lấp',
            'Huyện Đắk Song',
            'Huyện Krông Nô',
            'Huyện Tuy Đức',
            'Thị xã Gia Nghĩa'
        ),
        'DONG-NAI'        => array(
            'Huyện Cẩm Mỹ',
            'Huyện Định Quán',
            'Huyện Long Thành',
            'Huyện Nhơn Trạch',
            'Huyện Tân Phú',
            'Huyện Thống Nhất',
            'Huyện Trảng Bom',
            'Huyện Vĩnh Cửu',
            'Huyện Xuân Lộc',
            'Thành phố Biên Hòa',
            'Thị xã Long Khánh'
        ),
        'DONG-THAP'       => array(
            'Huyện Cao Lãnh',
            'Huyện Châu Thành',
            'Huyện Hồng Ngự',
            'Huyện Lai Vung',
            'Huyện Lấp Vò',
            'Huyện Tam Nông',
            'Huyện Tân Hồng',
            'Huyện Thanh Bình',
            'Huyện Tháp Mười',
            'Thành phố Cao Lãnh',
            'Thành phố Sa Đéc',
            'Thị xã Hồng Ngự'
        ),
        'DIEN-BIEN'       => array(
            'Huyện Điện Biên',
            'Huyện Điện Biên Đông',
            'Huyện Mường Ảng',
            'Huyện Mường Chà',
            'Huyện Mường Nhé',
            'Huyện Nậm Pồ',
            'Huyện Tủa Chùa',
            'Huyện Tuần Giáo',
            'Thành phố Điện Biên Phủ',
            'Thị Xã Mường Lay'
        ),
        'GIA-LAI'         => array(
            'Huyện Chư Păh',
            'Huyện Chư Prông',
            'Huyện Chư Pưh',
            'Huyện Chư Sê',
            'Huyện Đăk Đoa',
            'Huyện Đăk Pơ',
            'Huyện Đức Cơ',
            'Huyện Ia Grai',
            'Huyện Ia Pa',
            'Huyện KBang',
            'Huyện Kông Chro',
            'Huyện Krông Pa',
            'Huyện Mang Yang',
            'Huyện Phú Thiện',
            'Thành phố Pleiku',
            'Thị xã An Khê',
            'Thị xã Ayun Pa'
        ),
        'HA-GIANG'        => array(
            'Huyện Bắc Mê',
            'Huyện Bắc Quang',
            'Huyện Đồng Văn',
            'Huyện Hoàng Su Phì',
            'Huyện Mèo Vạc',
            'Huyện Quản Bạ',
            'Huyện Quang Bình',
            'Huyện Vị Xuyên',
            'Huyện Xín Mần',
            'Huyện Yên Minh',
            'Thành phố Hà Giang'
        ),
        'HA-NAM'          => array(
            'Huyện Bình Lục',
            'Huyện Duy Tiên',
            'Huyện Kim Bảng',
            'Huyện Lý Nhân',
            'Huyện Thanh Liêm',
            'Thành phố Phủ Lý'
        ),
        'HA-NOI'          => array(
            'Quận Ba Đình',
            'Quận Bắc Từ Liêm',
            'Quận Cầu Giấy',
            'Quận Đống Đa',
            'Quận Hà Đông',
            'Quận Hai Bà Trưng',
            'Quận Hoàn Kiếm',
            'Quận Hoàng Mai',
            'Quận Long Biên',
            'Quận Nam Từ Liêm',
            'Quận Tây Hồ',
            'Quận Thanh Xuân',
            'Huyện Ba Vì',
            'Huyện Chương Mỹ',
            'Huyện Đan Phượng',
            'Huyện Đông Anh',
            'Huyện Gia Lâm',
            'Huyện Hoài Đức',
            'Huyện Mê Linh',
            'Huyện Mỹ Đức',
            'Huyện Phú Xuyên',
            'Huyện Phúc Thọ',
            'Huyện Quốc Oai',
            'Huyện Sóc Sơn',
            'Huyện Thạch Thất',
            'Huyện Thanh Oai',
            'Huyện Thanh Trì',
            'Huyện Thường Tín',
            'Huyện Ứng Hòa',
            'Thị xã Sơn Tây'
        ),
        'HA-TINH'         => array(
            'Huyện Cẩm Xuyên',
            'Huyện Can Lộc',
            'Huyện Đức Thọ',
            'Huyện Hương Khê',
            'Huyện Hương Sơn',
            'Huyện Kỳ Anh',
            'Huyện Lộc Hà',
            'Huyện Nghi Xuân',
            'Huyện Thạch Hà',
            'Huyện Vũ Quang',
            'Thành phố Hà Tĩnh',
            'Thị xã Hồng Lĩnh',
            'Thị xã Kỳ Anh'
        ),
        'HAI-DUONG'       => array(
            'Huyện Bình Giang',
            'Huyện Cẩm Giàng',
            'Huyện Gia Lộc',
            'Huyện Kim Thành',
            'Huyện Kinh Môn',
            'Huyện Nam Sách',
            'Huyện Ninh Giang',
            'Huyện Thanh Hà',
            'Huyện Thanh Miện',
            'Huyện Tứ Kỳ',
            'Thành phố Hải Dương',
            'Thị xã Chí Linh'
        ),
        'HAI-PHONG'       => array(
            'Huyện An Dương',
            'Huyện An Lão',
            'Huyện Bạch Long Vĩ',
            'Huyện Cát Hải',
            'Huyện Kiến Thuỵ',
            'Huyện Thuỷ Nguyên',
            'Huyện Tiên Lãng',
            'Huyện Vĩnh Bảo',
            'Quận Đồ Sơn',
            'Quận Dương Kinh',
            'Quận Hải An',
            'Quận Hồng Bàng',
            'Quận Kiến An',
            'Quận Lê Chân',
            'Quận Ngô Quyền'
        ),
        'HOA-BINH'        => array(
            'Huyện Cao Phong',
            'Huyện Đà Bắc',
            'Huyện Kim Bôi',
            'Huyện Kỳ Sơn',
            'Huyện Lạc Sơn',
            'Huyện Lạc Thủy',
            'Huyện Lương Sơn',
            'Huyện Mai Châu',
            'Huyện Tân Lạc',
            'Huyện Yên Thủy',
            'Thành phố Hòa Bình'
        ),
        'HAU-GIANG'       => array(
            'Huyện Châu Thành',
            'Huyện Châu Thành A',
            'Huyện Long Mỹ',
            'Huyện Phụng Hiệp',
            'Huyện Vị Thuỷ',
            'Thành phố Vị Thanh',
            'Thị xã Long Mỹ',
            'Thị xã Ngã Bảy'
        ),
        'HUNG-YEN'        => array(
            'Huyện Ân Thi',
            'Huyện Khoái Châu',
            'Huyện Kim Động',
            'Huyện Mỹ Hào',
            'Huyện Phù Cừ',
            'Huyện Tiên Lữ',
            'Huyện Văn Giang',
            'Huyện Văn Lâm',
            'Huyện Yên Mỹ',
            'Thành phố Hưng Yên'
        ),
        'HO-CHI-MINH'     => array(
            'Quận 1',
            'Quận 10',
            'Quận 11',
            'Quận 12',
            'Quận 2',
            'Quận 3',
            'Quận 4',
            'Quận 5',
            'Quận 6',
            'Quận 7',
            'Quận 8',
            'Quận 9',
            'Quận Bình Tân',
            'Quận Bình Thạnh',
            'Quận Gò Vấp',
            'Quận Phú Nhuận',
            'Quận Tân Bình',
            'Quận Tân Phú',
            'Quận Thủ Đức',
            'Huyện Bình Chánh',
            'Huyện Cần Giờ',
            'Huyện Củ Chi',
            'Huyện Hóc Môn',
            'Huyện Nhà Bè'
        ),
        'KHANH-HOA'       => array(
            'Huyện Cam Lâm',
            'Huyện Diên Khánh',
            'Huyện Khánh Sơn',
            'Huyện Khánh Vĩnh',
            'Huyện Trường Sa',
            'Huyện Vạn Ninh',
            'Thành phố Cam Ranh',
            'Thành phố Nha Trang',
            'Thị xã Ninh Hòa'
        ),
        'KIEN-GIANG'      => array(
            'Huyện An Biên',
            'Huyện An Minh',
            'Huyện Châu Thành',
            'Huyện Giang Thành',
            'Huyện Giồng Riềng',
            'Huyện Gò Quao',
            'Huyện Hòn Đất',
            'Huyện Kiên Hải',
            'Huyện Kiên Lương',
            'Huyện Phú Quốc',
            'Huyện Tân Hiệp',
            'Huyện U Minh Thượng',
            'Huyện Vĩnh Thuận',
            'Thành phố Rạch Giá',
            'Thị xã Hà Tiên'
        ),
        'KON-TUM'         => array(
            'Huyện Đắk Glei',
            'Huyện Đắk Hà',
            'Huyện Đắk Tô',
            'Huyện Ia H Drai',
            'Huyện Kon Plông',
            'Huyện Kon Rẫy',
            'Huyện Ngọc Hồi',
            'Huyện Sa Thầy',
            'Huyện Tu Mơ Rông',
            'Thành phố Kon Tum'
        ),
        'LAI-CHAU'        => array(
            'Huyện Mường Tè',
            'Huyện Nậm Nhùn',
            'Huyện Phong Thổ',
            'Huyện Sìn Hồ',
            'Huyện Tam Đường',
            'Huyện Tân Uyên',
            'Huyện Than Uyên',
            'Thành phố Lai Châu'
        ),
        'LAO-CAI'         => array(
            'Huyện Bắc Hà',
            'Huyện Bảo Thắng',
            'Huyện Bảo Yên',
            'Huyện Bát Xát',
            'Huyện Mường Khương',
            'Huyện Sa Pa',
            'Huyện Si Ma Cai',
            'Huyện Văn Bàn',
            'Thành phố Lào Cai'
        ),
        'LANG-SON'        => array(
            'Huyện Bắc Sơn',
            'Huyện Bình Gia',
            'Huyện Cao Lộc',
            'Huyện Chi Lăng',
            'Huyện Đình Lập',
            'Huyện Hữu Lũng',
            'Huyện Lộc Bình',
            'Huyện Tràng Định',
            'Huyện Văn Lãng',
            'Huyện Văn Quan',
            'Thành phố Lạng Sơn'
        ),
        'LAM-DONG'        => array(
            'Huyện Bảo Lâm',
            'Huyện Cát Tiên',
            'Huyện Đạ Huoai',
            'Huyện Đạ Tẻh',
            'Huyện Đam Rông',
            'Huyện Di Linh',
            'Huyện Đơn Dương',
            'Huyện Đức Trọng',
            'Huyện Lạc Dương',
            'Huyện Lâm Hà',
            'Thành phố Bảo Lộc',
            'Thành phố Đà Lạt'
        ),
        'LONG-AN'         => array(
            'Huyện Bến Lức',
            'Huyện Cần Đước',
            'Huyện Cần Giuộc',
            'Huyện Châu Thành',
            'Huyện Đức Hòa',
            'Huyện Đức Huệ',
            'Huyện Mộc Hóa',
            'Huyện Tân Hưng',
            'Huyện Tân Thạnh',
            'Huyện Tân Trụ',
            'Huyện Thạnh Hóa',
            'Huyện Thủ Thừa',
            'Huyện Vĩnh Hưng',
            'Thành phố Tân An',
            'Thị xã Kiến Tường'
        ),
        'NAM-DINH'        => array(
            'Huyện Giao Thủy',
            'Huyện Hải Hậu',
            'Huyện Mỹ Lộc',
            'Huyện Nam Trực',
            'Huyện Nghĩa Hưng',
            'Huyện Trực Ninh',
            'Huyện Vụ Bản',
            'Huyện Xuân Trường',
            'Huyện Ý Yên',
            'Thành phố Nam Định'
        ),
        'NGHE-AN'         => array(
            'Huyện Anh Sơn',
            'Huyện Con Cuông',
            'Huyện Diễn Châu',
            'Huyện Đô Lương',
            'Huyện Hưng Nguyên',
            'Huyện Kỳ Sơn',
            'Huyện Nam Đàn',
            'Huyện Nghi Lộc',
            'Huyện Nghĩa Đàn',
            'Huyện Quế Phong',
            'Huyện Quỳ Châu',
            'Huyện Quỳ Hợp',
            'Huyện Quỳnh Lưu',
            'Huyện Tân Kỳ',
            'Huyện Thanh Chương',
            'Huyện Tương Dương',
            'Huyện Yên Thành',
            'Thành phố Vinh',
            'Thị xã Cửa Lò',
            'Thị xã Hoàng Mai',
            'Thị xã Thái Hoà'
        ),
        'NINH-BINH'       => array(
            'Huyện Gia Viễn',
            'Huyện Hoa Lư',
            'Huyện Kim Sơn',
            'Huyện Nho Quan',
            'Huyện Yên Khánh',
            'Huyện Yên Mô',
            'Thành phố Ninh Bình',
            'Thành phố Tam Điệp'
        ),
        'NINH-THUAN'      => array(
            'Huyện Bác Ái',
            'Huyện Ninh Hải',
            'Huyện Ninh Phước',
            'Huyện Ninh Sơn',
            'Huyện Thuận Bắc',
            'Huyện Thuận Nam',
            'Thành phố Phan Rang-Tháp Chàm'
        ),
        'PHU-THO'         => array(
            'Huyện Cẩm Khê',
            'Huyện Đoan Hùng',
            'Huyện Hạ Hoà',
            'Huyện Lâm Thao',
            'Huyện Phù Ninh',
            'Huyện Tam Nông',
            'Huyện Tân Sơn',
            'Huyện Thanh Ba',
            'Huyện Thanh Sơn',
            'Huyện Thanh Thuỷ',
            'Huyện Yên Lập',
            'Thành phố Việt Trì',
            'Thị xã Phú Thọ'
        ),
        'PHU-YEN'         => array(
            'Huyện Đông Hòa',
            'Huyện Đồng Xuân',
            'Huyện Phú Hoà',
            'Huyện Sơn Hòa',
            'Huyện Sông Hinh',
            'Huyện Tây Hoà',
            'Huyện Tuy An',
            'Thành phố Tuy Hoà',
            'Thị xã Sông Cầu'
        ),
        'QUANG-BINH'      => array(
            'Huyện Bố Trạch',
            'Huyện Lệ Thủy',
            'Huyện Minh Hóa',
            'Huyện Quảng Ninh',
            'Huyện Quảng Trạch',
            'Huyện Tuyên Hóa',
            'Thành Phố Đồng Hới',
            'Thị xã Ba Đồn'
        ),
        'QUANG-NAM'       => array(
            'Huyện Bắc Trà My',
            'Huyện Đại Lộc',
            'Huyện Đông Giang',
            'Huyện Duy Xuyên',
            'Huyện Hiệp Đức',
            'Huyện Nam Giang',
            'Huyện Nam Trà My',
            'Huyện Nông Sơn',
            'Huyện Núi Thành',
            'Huyện Phú Ninh',
            'Huyện Phước Sơn',
            'Huyện Quế Sơn',
            'Huyện Tây Giang',
            'Huyện Thăng Bình',
            'Huyện Tiên Phước',
            'Thành phố Hội An',
            'Thành phố Tam Kỳ',
            'Thị xã Điện Bàn'
        ),
        'QUANG-NGAI'      => array(
            'Huyện Ba Tơ',
            'Huyện Bình Sơn',
            'Huyện Đức Phổ',
            'Huyện Lý Sơn',
            'Huyện Minh Long',
            'Huyện Mộ Đức',
            'Huyện Nghĩa Hành',
            'Huyện Sơn Hà',
            'Huyện Sơn Tây',
            'Huyện Sơn Tịnh',
            'Huyện Tây Trà',
            'Huyện Trà Bồng',
            'Huyện Tư Nghĩa',
            'Thành phố Quảng Ngãi'
        ),
        'QUANG-NINH'      => array(
            'Huyện Ba Chẽ',
            'Huyện Bình Liêu',
            'Huyện Cô Tô',
            'Huyện Đầm Hà',
            'Huyện Hải Hà',
            'Huyện Hoành Bồ',
            'Huyện Tiên Yên',
            'Huyện Vân Đồn',
            'Thành phố Cẩm Phả',
            'Thành phố Hạ Long',
            'Thành phố Móng Cái',
            'Thành phố Uông Bí',
            'Thị xã Đông Triều',
            'Thị xã Quảng Yên'
        ),
        'QUANG-TRI'       => array(
            'Huyện Cam Lộ',
            'Huyện Cồn Cỏ',
            'Huyện Đa Krông',
            'Huyện Gio Linh',
            'Huyện Hải Lăng',
            'Huyện Hướng Hóa',
            'Huyện Triệu Phong',
            'Huyện Vĩnh Linh',
            'Thành phố Đông Hà',
            'Thị xã Quảng Trị'
        ),
        'SOC-TRANG'       => array(
            'Huyện Châu Thành',
            'Huyện Cù Lao Dung',
            'Huyện Kế Sách',
            'Huyện Long Phú',
            'Huyện Mỹ Tú',
            'Huyện Mỹ Xuyên',
            'Huyện Thạnh Trị',
            'Huyện Trần Đề',
            'Thành phố Sóc Trăng',
            'Thị xã Ngã Năm',
            'Thị xã Vĩnh Châu'
        ),
        'SON-LA'          => array(
            'Huyện Bắc Yên',
            'Huyện Mai Sơn',
            'Huyện Mộc Châu',
            'Huyện Mường La',
            'Huyện Phù Yên',
            'Huyện Quỳnh Nhai',
            'Huyện Sông Mã',
            'Huyện Sốp Cộp',
            'Huyện Thuận Châu',
            'Huyện Vân Hồ',
            'Huyện Yên Châu',
            'Thành phố Sơn La'
        ),
        'TAY-NINH'        => array(
            'Huyện Bến Cầu',
            'Huyện Châu Thành',
            'Huyện Dương Minh Châu',
            'Huyện Gò Dầu',
            'Huyện Hòa Thành',
            'Huyện Tân Biên',
            'Huyện Tân Châu',
            'Huyện Trảng Bàng',
            'Thành phố Tây Ninh'
        ),
        'THAI-BINH'       => array(
            'Huyện Đông Hưng',
            'Huyện Hưng Hà',
            'Huyện Kiến Xương',
            'Huyện Quỳnh Phụ',
            'Huyện Thái Thụy',
            'Huyện Tiền Hải',
            'Huyện Vũ Thư',
            'Thành phố Thái Bình'
        ),
        'THAI-NGUYEN'     => array(
            'Huyện Đại Từ',
            'Huyện Định Hóa',
            'Huyện Đồng Hỷ',
            'Huyện Phú Bình',
            'Huyện Phú Lương',
            'Huyện Võ Nhai',
            'Thành phố Sông Công',
            'Thành phố Thái Nguyên',
            'Thị xã Phổ Yên'
        ),
        'THANH-HOA'       => array(
            'Huyện Bá Thước',
            'Huyện Cẩm Thủy',
            'Huyện Đông Sơn',
            'Huyện Hà Trung',
            'Huyện Hậu Lộc',
            'Huyện Hoằng Hóa',
            'Huyện Lang Chánh',
            'Huyện Mường Lát',
            'Huyện Nga Sơn',
            'Huyện Ngọc Lặc',
            'Huyện Như Thanh',
            'Huyện Như Xuân',
            'Huyện Nông Cống',
            'Huyện Quan Hóa',
            'Huyện Quan Sơn',
            'Huyện Quảng Xương',
            'Huyện Thạch Thành',
            'Huyện Thiệu Hóa',
            'Huyện Thọ Xuân',
            'Huyện Thường Xuân',
            'Huyện Tĩnh Gia',
            'Huyện Triệu Sơn',
            'Huyện Vĩnh Lộc',
            'Huyện Yên Định',
            'Thành phố Thanh Hóa',
            'Thị xã Bỉm Sơn',
            'Thị xã Sầm Sơn'
        ),
        'THUA-THIEN-HUE'  => array(
            'Huyện A Lưới',
            'Huyện Nam Đông',
            'Huyện Phong Điền',
            'Huyện Phú Lộc',
            'Huyện Phú Vang',
            'Huyện Quảng Điền',
            'Thành phố Huế',
            'Thị xã Hương Thủy',
            'Thị xã Hương Trà'
        ),
        'TIEN-GIANG'      => array(
            'Huyện Cái Bè',
            'Huyện Cai Lậy',
            'Huyện Châu Thành',
            'Huyện Chợ Gạo',
            'Huyện Gò Công Đông',
            'Huyện Gò Công Tây',
            'Huyện Tân Phú Đông',
            'Huyện Tân Phước',
            'Thành phố Mỹ Tho',
            'Thị xã Cai Lậy',
            'Thị xã Gò Công'
        ),
        'TRA-VINH'        => array(
            'Huyện Càng Long',
            'Huyện Cầu Kè',
            'Huyện Cầu Ngang',
            'Huyện Châu Thành',
            'Huyện Duyên Hải',
            'Huyện Tiểu Cần',
            'Huyện Trà Cú',
            'Thành phố Trà Vinh',
            'Thị xã Duyên Hải'
        ),
        'TUYEN-QUANG'     => array(
            'Huyện Chiêm Hóa',
            'Huyện Hàm Yên',
            'Huyện Lâm Bình',
            'Huyện Nà Hang',
            'Huyện Sơn Dương',
            'Huyện Yên Sơn',
            'Thành phố Tuyên Quang'
        ),
        'VINH-LONG'       => array(
            'Huyện  Vũng Liêm',
            'Huyện Bình Tân',
            'Huyện Long Hồ',
            'Huyện Mang Thít',
            'Huyện Tam Bình',
            'Huyện Trà Ôn',
            'Thành phố Vĩnh Long',
            'Thị xã Bình Minh'
        ),
        'VINH-PHUC'       => array(
            'Huyện Bình Xuyên',
            'Huyện Lập Thạch',
            'Huyện Sông Lô',
            'Huyện Tam Đảo',
            'Huyện Tam Dương',
            'Huyện Vĩnh Tường',
            'Huyện Yên Lạc',
            'Thành phố Vĩnh Yên',
            'Thị xã Phúc Yên'
        ),
        'YEN-BAI'         => array(
            'Huyện Lục Yên',
            'Huyện Mù Căng Chải',
            'Huyện Trạm Tấu',
            'Huyện Trấn Yên',
            'Huyện Văn Chấn',
            'Huyện Văn Yên',
            'Huyện Yên Bình',
            'Thành phố Yên Bái',
            'Thị xã Nghĩa Lộ'
        ),
        'CHON-TINH-THANH' => array(
            'Chọn quận/huyện'
        ),
    );

    $states = array(
        'CHON-TINH-THANH' => 'Chọn tỉnh/thành phố',
        'HA-NOI'          => 'Hà Nội',
        'HO-CHI-MINH'     => 'Hồ Chí Minh',
        'AN-GIANG'        => 'An Giang',
        'BA-RIA-VUNG-TAU' => 'Bà Rịa Vũng Tàu',
        'BAC-LIEU'        => 'Bạc Liêu',
        'BAC-KAN'         => 'Bắc Kan',
        'BAC-GIANG'       => 'Bắc Giang',
        'BAC-NINH'        => 'Bắc Ninh',
        'BEN-TRE'         => 'Bến Tre',
        'BINH-DUONG'      => 'Bình Dương',
        'BINH-DINH'       => 'Bình Định',
        'BINH-PHUOC'      => 'Bình Phước',
        'BINH-THUAN'      => 'Bình Thuận',
        'CA-MAU'          => 'Cà Mau',
        'CAO-BANG'        => 'Cao Bằng',
        'CAN-THO'         => 'Cần Thơ',
        'DA-NANG'         => 'Đà Nẵng',
        'DAK-LAK'         => 'Dak Lak',
        'DAK-NONG'        => 'Dak Nong',
        'DONG-NAI'        => 'Đồng Nai',
        'DONG-THAP'       => 'Đồng Tháp',
        'DIEN-BIEN'       => 'Điện Biên',
        'GIA-LAI'         => 'Gia Lai',
        'HA-GIANG'        => 'Hà Giang',
        'HA-NAM'          => 'Hà Nam',
        'HA-TINH'         => 'Hà Tĩnh',
        'HAI-DUONG'       => 'Hải Dương',
        'HAI-PHONG'       => 'Hải Phòng',
        'HOA-BINH'        => 'Hòa Bình',
        'HAU-GIANG'       => 'Hậu Giang',
        'HUNG-YEN'        => 'Hưng Yên',
        'KHANH-HOA'       => 'Khánh Hòa',
        'KIEN-GIANG'      => 'Kiên Giang',
        'KON-TUM'         => 'Kon Tum',
        'LAI-CHAU'        => 'Lai Châu',
        'LAO-CAI'         => 'Lào Cai',
        'LANG-SON'        => 'Lạng Sơn',
        'LAM-DONG'        => 'Lâm Đồng',
        'LONG-AN'         => 'Long An',
        'NAM-DINH'        => 'Nam Định',
        'NGHE-AN'         => 'Nghệ An',
        'NINH-BINH'       => 'Ninh Bình',
        'NINH-THUAN'      => 'Ninh Thuận',
        'PHU-THO'         => 'Phú Thọ',
        'PHU-YEN'         => 'Phú Yên',
        'QUANG-BINH'      => 'Quảng Bình',
        'QUANG-NAM'       => 'Quảng Nam',
        'QUANG-NGAI'      => 'Quảng Ngãi',
        'QUANG-NINH'      => 'Quảng Ninh',
        'QUANG-TRI'       => 'Quảng Trị',
        'SOC-TRANG'       => 'Sóc Trăng',
        'SON-LA'          => 'Sơn La',
        'TAY-NINH'        => 'Tây Ninh',
        'THAI-BINH'       => 'Thái Bình',
        'THAI-NGUYEN'     => 'Thái Nguyên',
        'THANH-HOA'       => 'Thanh Hóa',
        'THUA-THIEN-HUE'  => 'Thừa Thiên Huế',
        'TIEN-GIANG'      => 'Tiền Giang',
        'TRA-VINH'        => 'Trà Vinh',
        'TUYEN-QUANG'     => 'Tuyên Quang',
        'VINH-LONG'       => 'Vĩnh Long',
        'VINH-PHUC'       => 'Vĩnh Phúc',
        'YEN-BAI'         => 'Yên Bái',
    );

    $state_chosen = array_map(function($item) {
        return rtrim(ltrim($item, " "), " ");
    }, explode(';', get_option('gs_options')['class_province']));

    $provinces = array(
        'CHON-TINH-THANH' => 'Chọn tỉnh/thành phố',
    );

    foreach($states as $k => $s){
        if(in_array($s, $state_chosen)) {
            $provinces[$k] = $s;
        }
    }

    $count_city = 1;
    $count_district = 1;
    foreach ($provinces as $key => $state){
        $arr['id'] = $state;
        $arr['text'] = $state;
        $arr['selected'] = false;
        $districts = $cities[$key];
        foreach ($districts as $district){
            $dis['id'] = $district;
            $dis['text'] = $district;
            $dis['selected'] = false;
            $dist[] = $dis;
            $count_district ++;
        }
        $arr['district'] = $dist;
        $local[] = $arr;
        $arr = [];
        $dist = [];
        $count_city ++;
    }
    return json_encode($local);
}


add_action( 'wp_ajax_get_day_of_week', 'get_day_of_week' );
add_action( 'wp_ajax_nopriv_get_day_of_week', 'get_day_of_week' );
function get_day_of_week()
{
    $arr = array(
        array(
            'id'    => 2,
            'text'  => 'Thứ 2'
        ),
        array(
            'id'    => 3,
            'text'  => 'Thứ 3'
        ),
        array(
            'id'    => 4,
            'text'  => 'Thứ 4'
        ),
        array(
            'id'    => 5,
            'text'  => 'Thứ 5'
        ),
        array(
            'id'    => 6,
            'text'  => 'Thứ 6'
        ),
        array(
            'id'    => 7,
            'text'  => 'Thứ 7'
        ),
        array(
            'id'    => 1,
            'text'  => 'Chủ nhật'
        )
    );

    return json_encode($arr);

    die;
}

add_action( 'wp_ajax_change_registration_classroom_status', 'change_registration_classroom_status' );
add_action( 'wp_ajax_nopriv_change_registration_classroom_status', 'change_registration_classroom_status' );
function change_registration_classroom_status()
{
    if( in_array('tutor', wp_get_current_user()->roles) ){
        global $wpdb;
        $table_name = $wpdb->prefix."gs_user_classrooms";
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : 'pending';

        update_post_meta($id, 'class_tutor', get_current_user_id());

        $wpdb->update( 
            $table_name, 
            array( 
                'status' => $status,   // string
            ), 
            array( 'id' => $id ), 
            array( 
                '%s',   // value1
            ), 
            array( '%d' ) 
        );

        if($wpdb->last_error !== '') {
            $wpdb->print_error();
            echo "Có lỗi xảy ra!";
        }else{
            echo "Cập nhật trạng thái lớp đăng ký lớp thành công!";
        }
    }else{
        echo "Bạn phải là gia sư để đăng ký lớp.";
    }
}

add_action( 'wp_ajax_remove_registration_classroom', 'remove_registration_classroom' );
add_action( 'wp_ajax_nopriv_remove_registration_classroom', 'remove_registration_classroom' );
function remove_registration_classroom()
{
    global $wpdb;
    $table_name = $wpdb->prefix."gs_user_classrooms";
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

    $wpdb->delete( $table_name, array( 'ID' => $id ) );

    if($wpdb->last_error !== '') {
        $wpdb->print_error();
        echo "Có lỗi xảy ra!";
    }else{
        echo "Cập nhật đăng ký lớp thành công!";
    }
}

function isTutorCompletedProfile($user_id) : bool
{
    // update_user_meta($userId, 'user_gender', $_REQUEST['user_gender']);
    // update_user_meta($userId, 'user_phone', $_REQUEST['user_phone']);
    // update_user_meta($userId, 'user_train_province', $_REQUEST['user_train_province']);
    // update_user_meta($userId, 'user_train_district', $_REQUEST['user_train_district']);
    // update_user_meta($userId, 'user_address', $_REQUEST['user_address']);

    // update_user_meta($userId, 'user_tutor_exp', $_REQUEST['user_tutor_exp']);
            // update_user_meta($userId, 'user_tutor_awards', $_REQUEST['user_tutor_awards']);

            // update_user_meta($userId, 'user_prof_role', $_REQUEST['user_prof_role']);
            // update_user_meta($userId, 'user_prof_format', $_REQUEST['user_prof_format']);
            // update_user_meta($userId, 'user_prof_price', $_REQUEST['user_prof_price']);
            // update_user_meta($userId, 'user_prof_subject', $_REQUEST['user_prof_subject']);
            // update_user_meta($userId, 'user_prof_classes', $_REQUEST['user_prof_classes']);
            // update_user_meta($userId, 'user_prof_schedule', $_REQUEST['user_prof_schedule']);

            // $old = get_the_author_meta( 'user_prof_schedule', $post_id );
            // $new = array();

            // $schedule = isset($_POST['user_schedule_day']) ? $_POST['user_schedule_day'] : "";
            // $start_date = isset($_POST['user_schedule_start']) ? $_POST['user_schedule_start'] : "";
            // $end_date = isset($_POST['user_schedule_end']) ? $_POST['user_schedule_end'] : "";

            // $count = count($schedule);

            // for ($i = 0; $i < $count; $i++) {
            //     if ($schedule[$i] != '') :
            //         $new[$i]['user_schedule_day'] = stripslashes(strip_tags($schedule[$i]));
            //         $new[$i]['user_schedule_start'] = stripslashes(strip_tags($start_date[$i]));
            //         $new[$i]['user_schedule_end'] = stripslashes(strip_tags($end_date[$i]));
            //     endif;
            // }

            // if (!empty($new) && $new != $old)
            //     update_user_meta($userId, 'user_prof_schedule', $new);
            // elseif (empty($new) && $old)
            //     delete_user_meta($userId, 'user_prof_schedule', $old);

            // $up_dir = GS_UPLOAD_DIR."/".$userId;
            // if (! is_dir($up_dir)) {
            //     mkdir( $up_dir, 0700 );
            // }

            // $user_prof_id_card = self::uploadSimple($up_dir, $_FILES['user_prof_id_card'], get_the_author_meta( 'user_prof_id_card', $userId ));
            // update_user_meta($userId, 'user_prof_id_card', $user_prof_id_card);

            // $user_prof_certificate = self::uploadSimple($up_dir, $_FILES['user_prof_certificate'], get_the_author_meta( 'user_prof_certificate', $userId ));
            // update_user_meta($userId, 'user_prof_certificate', $user_prof_certificate);

            // $user_prof_activation = self::uploadSimple($up_dir, $_FILES['user_prof_activation'], get_the_author_meta( 'user_prof_activation', $userId ));
            // update_user_meta($userId, 'user_prof_activation', $user_prof_activation);

            // update_user_meta($userId, 'user_prof_intro_video', $_REQUEST['user_prof_intro_video']);
    // if( !get_the_author_meta('user_phone', $user_id)) {

    // }

    return true;
}

function setClassroomCode($class_id) {
    $str_id = strlen((string) $class_id);
    $class_code = (string) $class_id;
    if($str_id <= 5) {
        for($i = 0; $i < 5 - $str_id; $i++ ) {
            $class_code = "0".$class_code;
        }
    }
    update_post_meta( $class_id, 'class_ID', "L".$class_code );
    return "L".$class_code;
}

// function getClassroomCode() {
//     echo get_post_meta( get_the_ID(  ), 'class_ID', true );
// }

function tutorUncompletedProfileMessage($user_id)
{
    if(!isTutorCompletedProfile($user_id)) {
        printf('<div class="gs_notice">%1$s</div>',
            __('Vui lòng cập nhật thông tin của bạn trước khi nhận lớp', GS_TEXTDOMAIN)
        );
    }
}

function isTutorSendedRequest($user_id, $classroom_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix."gs_user_classrooms";

    $results = $wpdb->get_results( 
        "SELECT id  FROM $table_name WHERE classroom_id = $classroom_id AND user_id = $user_id"
    );

    if($results && !empty($results) ) {
        return true;
    }

    return false;
}



function inputOptions($array)
{
    if(empty($array) || $array == "") {
        return "";
    }
    $output = "";
    foreach($array as $key => $item) {
        if($item === true) {
            $output = $output." ".$key.'="true"';
        }elseif($item === false) {
            $output = $output." ".$key.'="false"';
        }else{
            $output = $output." ".$key.'="'.$item.'"';
        }
    }
    return $output;
}

function data_tree($data, $parent_id = 0){
    $return = [];
    foreach ($data as $key => $option){
        if($option->parent == $parent_id){
            $return[$option->term_id] = $option;
            unset($data[$key]);
            $return[$option->term_id]->items = data_tree($data,$option->term_id);
        }
    }
    return $return;
}

function getClassroomAddressIframe($class_ID)
{
    $address = urlencode(get_post_meta( $class_ID, 'class_address', true ) ? get_post_meta( $class_ID, 'class_address', true ) : "62 Đình Thôn- Phường Mỹ Đình 1- Q Nam Từ Liêm Hà Nội");
    printf('<iframe frameborder="0" width="600px" height="auto" src="https://www.google.com/maps/embed/v1/place?key=%1$s&q=%2$s&zoom=14"></iframe>',
        get_option( 'gs_options' )['google_api_key'],
        utf8_decode($address)
    );

}