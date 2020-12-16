<?php 

add_action( 'wp_ajax_register_classroom', 'tutor_register_classroom_action' );
add_action( 'wp_ajax_nopriv_register_classroom', 'tutor_register_classroom_action' );

function tutor_register_classroom_action () {
    global $wpdb;
	
	$user = get_current_user_id();
    $class = intval($_POST['classroom_id']);
    $status = 'pending';
	
	$table_name = $wpdb->prefix . 'gs_user_classrooms';
	
	if(is_user_logged_in()) {
        if(!isTutorSendedRequest($user, $class)) {
            $roles = ( array ) $user->roles;
            $wpdb->insert( 
                $table_name, 
                array( 
                    'user_id' => $user, 
                    'classroom_id' => $class, 
                    'status' => $status, 
                    'created_at' => current_time( 'mysql' ),
                ),
                array(
                    '%d',
                    '%d',
                    '%s'
                )
            );
            echo "Đã đề nghị"; die;
        }
        echo "Đã đề nghị"; die;
    }else{
        echo "Có lỗi"; die;
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