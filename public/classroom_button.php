<?php 

add_action( 'wp_ajax_register_classroom', 'tutor_register_classroom_action' );
add_action( 'wp_ajax_nopriv_register_classroom', 'tutor_register_classroom_action' );

function tutor_register_classroom_action () {
    global $wpdb;
	
	$user = get_current_user_id();
    $class = intval($_POST['classroom_id']);
    $status = 'pending';
	
    $table_name = $wpdb->prefix . 'gs_user_classrooms';
    $user_data = get_user_by( 'ID', $user );
	
	if(is_user_logged_in()) {
        if(!isTutorSendedRequest($user, $class)) {
            $roles = ( array ) $user->roles;
            $wpdb->insert( 
                $table_name, 
                array( 
                    'user_id' => $user, 
                    'user_name' => $user_data->first_name . ' ' . $user_data->last_name, 
                    'user_phone' => esc_attr( get_the_author_meta( 'user_phone', $user ) ), 
                    'user_email' => $user_data->user_email, 
                    'classroom_id' => $class, 
                    'classroom_name' => get_the_title( $class ),
                    'classroom_code' => get_post_meta( $class, 'class_ID', true ),
                    'status' => $status, 
                    'created_at' => current_time( 'mysql' ),
                ),
                array(
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );
            echo __('Đăng ký thành công', GS_TEXTDOMAIN); die;
        }
        echo __('Đã đăng ký lớp trước đó', GS_TEXTDOMAIN); die;
    }else{
        echo "0"; die;
    }
}

// function get_tutor_classrooms($user_id) {
//     global $wpdb;

//     $table_name = $wpdb->prefix . 'gs_user_classrooms';
	
// 	$query = <<<SQL
//     SELECT
//         classroom_id, status
//     FROM
//         $table_name class
//         WHERE
//             class.user_id = $user_id
//     SQL;
         
//     /** @var array $result this will give us the ID and the other meta_field if any of all post types selected */
//     $result = $wpdb->get_results( $query, ARRAY_A );

//     return $result;
// }