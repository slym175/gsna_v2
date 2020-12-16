<?php 

// add_action( 'wp_ajax_get_subjects', 'get_subjects' );
// add_action( 'wp_ajax_nopriv_get_subjects', 'get_subjects' );

function get_subjects()
{
    $class_subject = get_terms( array(
        'taxonomy'      => 'class_subject',
        'hide_empty'    => false
    ) );

    $subjects = array();
    // $tree = data_tree($class_subject);

    // foreach($tree as $key => $subject) {
    //     if( $subject->items ) {
    //         $subjects[$key]['text'] = $subject->name;
    //         $children = [];
    //         foreach($subject->items as $k => $child) {
    //             $children[$k]['id'] = $child->term_id;
    //             $children[$k]['text'] = $child->name;
    //         }
    //         $subjects[$key]['children'] = $children;
    //     }else{
    //         $subjects[$key]['id'] = $subject->term_id;
    //         $subjects[$key]['text'] = $subject->name;
    //     }
    // }
    $subjects[0]['id'] = "0";
    $subjects[0]['text'] = "Chọn môn học";

    foreach($class_subject as $key => $subject) {
        $subjects[$key + 1]['id'] = $subject->term_id;
        $subjects[$key + 1]['text'] = $subject->name;
    }

    return json_encode($subjects, JSON_OBJECT_AS_ARRAY);
}

function get_targets()
{
    $targ_value = array_map(function($item) {
        return rtrim(ltrim($item, " "), " ");
    }, explode(';', get_option('gs_options')['tutor_targets']));

    $targets = array();

    $targets[0]['id'] = "0";
    $targets[0]['text'] = "Chọn đối tượng";

    foreach($targ_value as $key => $targ) {
        $targets[$key + 1]['id'] = $targ;
        $targets[$key + 1]['text'] = $targ;
    }

    return json_encode($targets, JSON_OBJECT_AS_ARRAY);
}

function get_caphoc()
{
    $targ_value = array_map(function($item) {
        return rtrim(ltrim($item, " "), " ");
    }, explode(';', get_option('gs_options')['class_caphoc']));

    $targets = array();

    $targets[0]['id'] = "0";
    $targets[0]['text'] = "Chọn cấp học";

    foreach($targ_value as $key => $targ) {
        $targets[$key + 1]['id'] = $targ;
        $targets[$key + 1]['text'] = $targ;
    }

    return json_encode($targets, JSON_OBJECT_AS_ARRAY);
}

function get_format()
{
    $targ_value = array_map(function($item) {
        return rtrim(ltrim($item, " "), " ");
    }, explode(';', get_option('gs_options')['tutor_formats']));

    $targets = array();

    $targets[0]['id'] = "0";
    $targets[0]['text'] = "Hình thức dạy";

    foreach($targ_value as $key => $targ) {
        $targets[$key + 1]['id'] = $targ;
        $targets[$key + 1]['text'] = $targ;
    }

    return json_encode($targets, JSON_OBJECT_AS_ARRAY);
}


// add_action( 'wp_ajax_get_all_themes', 'get_all_themes' );
// add_action( 'wp_ajax_nopriv_get_all_themes', 'get_all_themes' );

function get_all_themes()
{
    $class_themes = get_terms( array(
        'taxonomy'      => 'class_theme',
        'hide_empty'    => false
    ) );

    $themes = array();
    $themes[0]['id'] = "0";
    $themes[0]['text'] = "Chọn chủ đề";

    foreach($class_themes as $key => $theme) {
        $themes[$key + 1]['id'] = $theme->term_id;
        $themes[$key + 1]['text'] = $theme->name;
    }

    return json_encode($themes);
}

// add_action( 'wp_ajax_classroom_filter', 'ajax_classroom_filter' );
// add_action( 'wp_ajax_nopriv_classroom_filter', 'ajax_classroom_filter' );

// function ajax_classroom_filter()
// {
//     $filter_province = isset($_POST['filter_province']) && $_POST['filter_province'] != "Chọn tỉnh/thành phố" ? $_POST['filter_province'] : "";
//     $filter_district = isset($_POST['filter_district']) && $_POST['filter_province'] != "Chọn quận/huyện" ? $_POST['filter_district'] : "";
//     $filter_subject = isset($_POST['filter_subject']) && $_POST['filter_province'] != 0 ? $_POST['filter_subject'] : "";
//     $filter_theme = isset($_POST['filter_theme']) ? $_POST['filter_theme'] : "";
//     $filter_target = isset($_POST['filter_target']) ? $_POST['filter_target'] : "";

//     $args = array(
//         'post_type'         => 'classroom',
//         'post_status'       => array('publish'),
//         'orderby'           => 'published_date',
//         'order'             => 'DESC',
//         'posts_per_page'    => get_option( 'gs_options' )['classes_per_page'] ? get_option( 'gs_options' )['classes_per_page'] : 12,
//         'tax_query'         => array(
//             'relation'      => 'OR',
//             array(
//                 'taxonomy'  => 'class_subject',
//                 'field'     => 'term_id',
//                 'terms'     => intval($filter_subject),
//             ),
//             array(
//                 'taxonomy'  => 'class_theme',
//                 'field'     => 'term_id',
//                 'terms'     => intval($filter_theme),
//             ),
//         ),
//         'meta_query'        => array(
//             'relation'      => 'OR',
//             array(
//                 'key'       => 'class_target',
//                 'value'     => $filter_target,
//                 'compare'   => 'IN',
//             ),
//             array(
//                 'key'       => 'class_address',
//                 'value'     => $filter_province,
//                 'compare'   => 'LIKE',
//             ),
//             array(
//                 'key'       => 'class_address',
//                 'value'     => $filter_district,
//                 'compare'   => 'LIKE',
//             )
//         ),
//     );
//     $query = new WP_Query( $args );

//     echo json_encode($query); die;
// }
