<?php

if(!class_exists('ClassroomType')) {
    class ClassroomType {

        public function __construct()
        {
            add_action( 'init', array($this, 'classroom_type_init') );
            add_action( 'add_meta_boxes', array($this, 'classroom_meta_box') );
            // add_action('admin_footer-post.php', array($this, 'wpb_append_post_status_list') );
        }

        public static function classroom_type_init(){

            // Classroom ==============================================================
            $class_labels = array(
                'name'                  => __( 'Lớp học', GS_TEXTDOMAIN ),
                'singular_name'         => __( 'Lớp học', GS_TEXTDOMAIN ),
                'menu_name'             => __( 'Lớp học', GS_TEXTDOMAIN ),
                'name_admin_bar'        => __( 'Lớp học', GS_TEXTDOMAIN ),
                'add_new'               => __( 'Thêm mới', GS_TEXTDOMAIN ),
                'add_new_item'          => __( 'Thêm lớp học', GS_TEXTDOMAIN ),
                'new_item'              => __( 'Lớp học mới', GS_TEXTDOMAIN ),
                'edit_item'             => __( 'Sửa lớp học', GS_TEXTDOMAIN ),
                'view_item'             => __( 'Xem lớp học', GS_TEXTDOMAIN ),
                'all_items'             => __( 'Tất cả lớp học', GS_TEXTDOMAIN ),
                'search_items'          => __( 'Tìm kiếm lớp học', GS_TEXTDOMAIN ),
                'parent_item_colon'     => __( 'Lớp học cha:', GS_TEXTDOMAIN ),
                'not_found'             => __( 'Không tìm thấy lớp học.', GS_TEXTDOMAIN ),
                'not_found_in_trash'    => __( 'Không có lớp học nào được xóa.', GS_TEXTDOMAIN ),
                'insert_into_item'      => __( 'Chèn lớp học vào', GS_TEXTDOMAIN),
                'uploaded_to_this_item' => __( 'Lớp học được tải lên', GS_TEXTDOMAIN),
                'featured_image'        => __( 'Ảnh đại diện', GS_TEXTDOMAIN),
                'set_featured_image'    => __( 'Thêm ảnh đại diện', GS_TEXTDOMAIN),
                'menu_icon'             => 'dashicons-welcome-learn-more'
            );
    
            $class_args = array(
                'labels'             => $class_labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => get_option( 'location_permalink' ,'gs_classroom' ) ),
                'has_archive'        => true,
                'hierarchical'       => true,
                'menu_position'      => 30,
                'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt','links','page-attributes', 'author', 'post-formats'),
                'menu_icon'          => 'dashicons-welcome-learn-more',
                'exclude_from_search'=>true,
    
            );
    
            register_post_type( 'classroom', $class_args );

            // Subjects Taxonomy ==============================================================
            $subject_labels = array(
                'name'                       => __( 'Môn học', GS_TEXTDOMAIN ),
                'singular_name'              => __( 'Môn học',  GS_TEXTDOMAIN ),
                'search_items'               => __( 'Tìm kiếm môn học' , GS_TEXTDOMAIN),
                'popular_items'              => __( 'Môn học phổ biến' , GS_TEXTDOMAIN),
                'all_items'                  => __( 'Tất cả môn học', GS_TEXTDOMAIN ),
                // 'parent_item'                => __( 'Môn học cha', GS_TEXTDOMAIN ),
                // 'parent_item_colon'          => __( 'Môn học cha', GS_TEXTDOMAIN ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Sửa môn học' , GS_TEXTDOMAIN),
                'update_item'                => __( 'Cập nhật môn học' , GS_TEXTDOMAIN),
                'add_new_item'               => __( 'Thêm mới môn học', GS_TEXTDOMAIN ),
                'new_item_name'              => __( 'Tên môn học mới', GS_TEXTDOMAIN ),
                'separate_items_with_commas' => __( 'Separate subjects with commas' , GS_TEXTDOMAIN),
                'add_or_remove_items'        => __( 'Thêm hoặc xóa môn học', GS_TEXTDOMAIN ),
                'choose_from_most_used'      => __( 'Choose from the most used subjects', GS_TEXTDOMAIN ),
                'not_found'                  => __( 'No Pickup Subjects.', GS_TEXTDOMAIN ),
                'menu_name'                  => __( 'Môn học', GS_TEXTDOMAIN ),
            );
    
            $subject_args = array(
                'hierarchical'          => true,
                'labels'                => $subject_labels,
                'show_ui'               => true,
                'show_in_rest'          => true,
                'show_admin_column'     => true,
                'query_var'             => true,
            );
    
            register_taxonomy( 'class_subject', 'classroom', $subject_args );

            //  Theme Taxonomy ==============================================================
            $theme_labels = array(
                'name'                       => __( 'Chủ đề', GS_TEXTDOMAIN ),
                'singular_name'              => __( 'Chủ đề',  GS_TEXTDOMAIN ),
                'search_items'               => __( 'Tìm kiếm chủ đề' , GS_TEXTDOMAIN),
                'popular_items'              => __( 'Chủ đề phổ biến' , GS_TEXTDOMAIN),
                'all_items'                  => __( 'Tất cả chủ đề', GS_TEXTDOMAIN ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Sửa chủ đề' , GS_TEXTDOMAIN),
                'update_item'                => __( 'Cập nhật chủ đề' , GS_TEXTDOMAIN),
                'add_new_item'               => __( 'Thêm mới chủ đề', GS_TEXTDOMAIN ),
                'new_item_name'              => __( 'Tên chủ đề mới', GS_TEXTDOMAIN ),
                'separate_items_with_commas' => __( 'Separate Themes with commas' , GS_TEXTDOMAIN),
                'add_or_remove_items'        => __( 'Thêm hoặc xóa chủ đề', GS_TEXTDOMAIN ),
                'choose_from_most_used'      => __( 'Choose from the most used Themes', GS_TEXTDOMAIN ),
                'not_found'                  => __( 'No Pickup Themes.', GS_TEXTDOMAIN ),
                'menu_name'                  => __( 'Chủ đề', GS_TEXTDOMAIN ),
            );
    
            $theme_args = array(
                'hierarchical'          => false,
                'labels'                => $theme_labels,
                'show_ui'               => true,
                'show_in_rest'          => true,
                'show_admin_column'     => true,
                'query_var'             => true,
            );
    
            register_taxonomy( 'class_theme', 'classroom', $theme_args );

            // register_post_status( 'teaching', array(
            //     'label'                     => _x( 'Teaching', 'classroom', GS_TEXTDOMAIN ),
            //     'public'                    => true,
            //     'label_count'               => _n_noop( 'Teaching <span class="count">(%s)</span>', 'Teaching <span class="count">(%s)</span>', GS_TEXTDOMAIN ),
            //     'post_type'                 => array( 'classroom' ), // Define one or more post types the status can be applied to.
            //     'show_in_admin_all_list'    => true,
            //     'show_in_admin_status_list' => true,
            //     'show_in_metabox_dropdown'  => true,
            //     'show_in_inline_dropdown'   => true,
            //     'dashicon'                  => 'dashicons-yes',
            // ) );
        }

        public static function classroom_meta_box()
        {
            //add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
            add_meta_box( 'class_options', __( 'Thông tin mở rộng', GS_TEXTDOMAIN ), 'view_render_form', 'classroom', 'advanced', 'high', array());
            add_meta_box( 'class_tutor', __( 'Gia sư', GS_TEXTDOMAIN ), 'view_render_tutor', 'classroom', 'advanced', 'high', array());
            add_meta_box( 'class_schedule', __( 'Lịch giảng dạy', GS_TEXTDOMAIN ), 'view_render_repeater', 'classroom', 'advanced', 'high', array());
        }

        // Using jQuery to add it to post status dropdown

        // public static function wpb_append_post_status_list()
        // {
        //     global $post;
        //     $complete = '';
        //     $label = '';
        //     if($post->post_type == 'classroom'){
        //         if($post->post_status == 'teaching'){
        //             $complete = ' selected="selected"';
        //             $label = '<span id="post-status-display"> '. __('Đang dạy', GS_TEXTDOMAIN) .'</span>';
        //         }
        //         echo '
        //             <script>
        //                 jQuery(document).ready(function($){
        //                     $("select#post_status").append("<option value=\"teaching\" '.$complete.'>'. __('Đang dạy', GS_TEXTDOMAIN) .'</option>");
        //                     $(".misc-pub-section label").append("'.$label.'");
        //                 });
        //             </script>
        //         ';
        //     }
        // }
    }
    new ClassroomType;
}



function view_render_form($post, $callback_args)
{
    ?> <table>
    <tr class="form-row">
        <td><label for="class_ID">Mã lớp</label></td>
        <td><input class="regular-text form-control" type="text" name="class_ID" id="class_ID"
                value="<?= get_post_meta( $post->ID, 'class_ID', true ) ?>"></td>
    </tr>
    <tr class="form-row">
        <td><label for="class_times">Thời lượng giảng dạy</label></td>
        <td><input class="regular-text form-control" type="text" name="class_times" id="class_times"
                value="<?= get_post_meta( $post->ID, 'class_times', true ) ?>"></td>
    </tr>
    <!-- <tr class="form-row">
            <td><label for="class_schedule">Lịch trình dạy</label></td>
            <td>
                <?php //$schedules = array(
                    //'t2-0' => 'Sáng thứ 2',
                    //'t2-1' => 'Chiều thứ 2',
                    //'t2-2' => 'Tối thứ 2',
                    //'t3-0' => 'Sáng thứ 3',
                    //'t3-1' => 'Chiều thứ 3',
                    //'t3-2' => 'Tối thứ 3',
                    //'t4-0' => 'Sáng thứ 4',
                    //'t4-1' => 'Chiều thứ 4',
                    //'t4-2' => 'Tối thứ 4',
                    //'t5-0' => 'Sáng thứ 5',
                    //'t5-1' => 'Chiều thứ 5',
                    //'t5-2' => 'Tối thứ 5',
                    //'t6-0' => 'Sáng thứ 6',
                    //'t6-1' => 'Chiều thứ 6',
                    //'t6-2' => 'Tối thứ 6',
                    //'t7-0' => 'Sáng thứ 7',
                    //'t7-1' => 'Chiều thứ 7',
                    //'t7-2' => 'Tối thứ 7',
                    //'cn-0' => 'Sáng chủ nhật',
                    //'cn-1' => 'Chiều chủ nhật',
                    //'cn-2' => 'Tối chủ nhật',
                //);?>
                <select class="regular-text form-control" name="class_schedule[]" id="class_schedule" multiple>
                    <?php //foreach($schedules as $key => $schedule) { ?>
                    <?php //} ?>
                </select>
            </td>
        </tr> -->
    <tr class="form-row">
        <td><label for="class_price">Học phí (/buổi)</label></td>
        <td><input class="regular-text form-control" type="number" name="class_price" id="class_price"
                value="<?= get_post_meta( $post->ID, 'class_price', true ) ?>"></td>
    </tr>
    <tr class="form-row">
        <td><label for="class_fee">Phí nhận lớp</label></td>
        <td><input class="regular-text form-control" type="number" name="class_fee" id="class_fee"
                value="<?= get_post_meta( $post->ID, 'class_fee', true ) ?>"></td>
    </tr>
    <tr class="form-row">
        <td><label for="class_students">Số học viên</label></td>
        <td><input class="regular-text form-control" type="number" name="class_students" id="class_students"
                value="<?= get_post_meta( $post->ID, 'class_students', true ) ?>"></td>
    </tr>
    <tr class="form-row">
        <td><label>Giới tính</label></td>
        <td>
            <input class="regular-text form-control" type="radio" name="class_gender" id="class_gender_0" value="0"
                <?= get_post_meta( $post->ID, 'class_gender', true ) == 0 ? "checked" : "" ?>><label
                for="class_gender_0">Nam</label>
            <input class="regular-text form-control" type="radio" name="class_gender" id="class_gender_1" value="1"
                <?= get_post_meta( $post->ID, 'class_gender', true ) == 1 ? "checked" : "" ?>><label
                for="class_gender_1">Nữ</label>
            <input class="regular-text form-control" type="radio" name="class_gender" id="class_gender_2" value="2"
                <?= get_post_meta( $post->ID, 'class_gender', true ) == 2 ? "checked" : "" ?>><label
                for="class_gender_2">Không yêu cầu</label>
        </td>
    </tr>
    <tr class="form-row">
        <td><label for="class_target">Đối tượng giảng dạy</label></td>
        <td>
            <?php 
                    $targ = get_post_meta( $post->ID, 'class_target', true ) ? get_post_meta( $post->ID, 'class_target', true ) : "";

                    $targ_value = array_map(function($item) {
                        return rtrim(ltrim($item, " "), " ");
                    }, explode(';', get_option('gs_options')['tutor_targets']));
                ?>
            <select class="regular-text form-control" name="class_target" id="class_target">
                <?php foreach($targ_value as $tar) : ?>
                <option value="<?= $tar ?>" <?= $tar == $targ ? 'selected' : '' ?>><?= $tar ?></option>
                <?php endforeach ?>
            </select>
        </td>
    </tr>
    <tr class="form-row">
        <td><label for="class_address">Địa điểm dạy</label></td>
        <td><textarea class="regular-text form-control" type="text" rows="4" name="class_address"
                id="class_address"><?= get_post_meta( $post->ID, 'class_address', true )?></textarea></td>
    </tr>
    <tr class="form-row">
        <td><label for="class_format">Hình thức học</label></td>
        <td>
            <?php 
                    $formats = array_map(function($item) {
                        return rtrim(ltrim($item, " "), " ");
                    }, explode(';', get_option('gs_options')['tutor_formats']));
                ?>

            <select class="regular-text form-control" name="class_format" id="class_format"
                value="<?= get_post_meta( $post->ID, 'class_format', true ) ?>">
                <?php foreach($formats as $key => $format) { ?>
                <option value="<?= $format ?>"
                    <?= get_post_meta( $post->ID, 'class_format', true ) == $key ? "selected" : "" ?>><?= $format ?>
                </option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr class="form-row">
        <td><label for="class_caphoc">Cấp học</label></td>
        <td>
            <?php 

                    $caphocs = array_map(function($item) {
                        return rtrim(ltrim($item, " "), " ");
                    }, explode(';', get_option('gs_options')['class_caphoc']));

                    // print_r(get_post_meta( $post->ID, 'class_caphoc', true ));
                    
                ?>
            <select class="regular-text form-control" name="class_caphoc" id="class_caphoc">
                <?php foreach($caphocs as $key => $ch) { ?>
                <option value="<?= $ch ?>"
                    <?= get_post_meta( $post->ID, 'class_caphoc', true ) == $ch ? "selected" : "" ?>><?= $ch ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <!-- <tr class="form-row">
        <td><label for="class_level">Trình độ</label></td>
        <td>
            <?php 
                    // $levels = array_map(function($item) {
                    //     return rtrim(ltrim($item, " "), " ");
                    // }, explode(';', get_option('gs_options')['class_levels']));
                    
                    // $lev = is_array(get_post_meta( $post->ID, 'class_level', true )) ? get_post_meta( $post->ID, 'class_level', true ) : array();
                ?>
            <select class="regular-text form-control" name="class_level[]" id="class_level" multiple>
                <?php // foreach($levels as $key => $level) { ?>
                <option value="<?php // echo $level ?>" <?php // echo in_array($level, $lev) ? "selected" : "" ?>><?php // echo $level ?></option>
                <?php // } ?>
            </select>
        </td>
    </tr> -->
    <tr class="form-row">
        <td><label for="class_note">Ghi chú</label></td>
        <td><textarea class="regular-text form-control" type="text" rows="4" name="class_note"
                id="class_note"><?= get_post_meta( $post->ID, 'class_note', true ) ?></textarea></td>
    </tr>
</table>
<?php
}

function view_render_tutor($post, $callback_args)
{
    $args = array(
        'role'      => 'tutor',
        'order_by'  => 'display_name'
    );
    $users = get_users( $args );
    ?>
<table>
    <tr class="form-row">
        <td>
            <select class="regular-text form-control" name="class_tutor" id="class_tutor"
                value="<?= get_post_meta( $post->ID, 'class_tutor', true ) ?>">
                <option value="0" <?= get_post_meta( $post->ID, 'class_tutor', true ) == 0 ? "checked" : "" ?>>
                    <?= __('Chưa xác định', GS_TEXTDOMAIN) ?></option>
                <?php foreach($users as $key => $user) { ?>
                <option value="<?= $user->data->ID ?>"
                    <?= get_post_meta( $post->ID, 'class_tutor', true ) == $user->data->ID ? "checked" : "" ?>>
                    <?= $user->data->display_name ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
</table>
<?php
}

function view_render_repeater($post, $callback_args)
{
    $repeatable_fields = get_post_meta($post->ID, 'class_schedule', true);

    wp_nonce_field('hhs_repeatable_meta_box_nonce', 'hhs_repeatable_meta_box_nonce');

    ?>
<table id="class_schedule_table">
    <thead>
        <tr>
            <th width="5%"></th>
            <th width="30%">Thời gian</th>
            <th width="30%">Bắt đầu</th>
            <th width="30%">Kết thúc</th>
            <th width="5%"></th>
        </tr>
    </thead>
    <?php 
            $day_of_week = array('Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật');
            ?>
    <?php if ($repeatable_fields) :
                foreach ($repeatable_fields as $field) : ?>
    <tr class="form-row">
        <td>
            <a href="javascript:void(0)" onclick="insertRepeaterRow(this)"><i
                    class="dashicons-before dashicons-insert"></i></a>
        </td>
        <td class="width: 50%">
            <select class="regular-text form-control class_schedule_day" name="class_schedule_day[]"
                value="<?php echo $field['class_schedule_day'] ?>">
                <?php foreach($day_of_week as $key => $day) : ?>
                <option value="<?= $day ?>" <?= $day == $field['class_schedule_day'] ? "selected" : ""?>><?= $day ?>
                </option>
                <?php endforeach ?>
            </select>
        </td>
        <td>
            <input class="regular-text form-control class_schedule_start" type="time" name="class_schedule_start[]"
                value="<?php echo $field['class_schedule_start'] ?>">
        </td>
        <td>
            <input class="regular-text form-control class_schedule_end" type="time" name="class_schedule_end[]"
                value="<?php echo $field['class_schedule_end'] ?>">
        </td>
        <td>
            <a href="javascript:void(0)" onclick="removeRepeaterRow(this)"><i
                    class="dashicons-before dashicons-dismiss"></i></a>
        </td>
    </tr>
    <?php endforeach ?>
    <?php else : ?>
    <tr class="form-row">
        <td>
            <a href="javascript:void(0)" onclick="insertRepeaterRow(this)"><i
                    class="dashicons-before dashicons-insert"></i></a>
        </td>
        <td class="width: 50%">
            <select class="regular-text form-control class_schedule_day" name="class_schedule_day[]">
                <?php foreach($day_of_week as $day) : ?>
                <option value="<?= $day ?>"><?= $day ?></option>
                <?php endforeach ?>
            </select>
        </td>
        <td>
            <input class="regular-text form-control class_schedule_start" type="time" name="class_schedule_start[]">
        </td>
        <td>
            <input class="regular-text form-control class_schedule_end" type="time" name="class_schedule_end[]">
        </td>
        <td>
            <a href="javascript:void(0)" onclick="removeRepeaterRow(this)"><i
                    class="dashicons-before dashicons-dismiss"></i></a>
        </td>
    </tr>
    <?php endif ?>
</table>
<?php
}

function gs_post_meta_save( $post_id )
{
    if (!isset($_POST['hhs_repeatable_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['hhs_repeatable_meta_box_nonce'], 'hhs_repeatable_meta_box_nonce'))
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    $old = get_post_meta($post_id, 'class_schedule', true);
    $new = array();

    $schedule = isset($_POST['class_schedule_day']) ? $_POST['class_schedule_day'] : "";
    $start_date = isset($_POST['class_schedule_start']) ? $_POST['class_schedule_start'] : "";
    $end_date = isset($_POST['class_schedule_end']) ? $_POST['class_schedule_end'] : "";

    $count = count($schedule);

    for ($i = 0; $i < $count; $i++) {
        if ($schedule[$i] != '') :
            $new[$i]['class_schedule_day'] = stripslashes(strip_tags($schedule[$i]));
            $new[$i]['class_schedule_start'] = stripslashes(strip_tags($start_date[$i]));
            $new[$i]['class_schedule_end'] = stripslashes(strip_tags($end_date[$i]));
        endif;
    }

    if (!empty($new) && $new != $old)
        update_post_meta($post_id, 'class_schedule', $new);
    elseif (empty($new) && $old)
        delete_post_meta($post_id, 'class_schedule', $old);

    // update_post_meta( $post_id, 'class_ID', setClassroomCode($post_id) );
    if(!isset($_POST['class_ID'])) {
        $_POST['class_ID'] = "";
    }
    $class_ID = sanitize_text_field( $_POST['class_ID'] );
    update_post_meta( $post_id, 'class_ID', $class_ID );
    // setClassroomCode($post_id);

    if(!isset($_POST['class_times'])) {
        $_POST['class_times'] = "";
    }
    $class_times = sanitize_text_field( $_POST['class_times'] );
    update_post_meta( $post_id, 'class_times', $class_times );

    // if(!isset($_POST['class_schedule'])) {
    //     $_POST['class_schedule'] = array();
    // }
    // $class_schedules = array_map( 'sanitize_text_field', wp_unslash( $_POST['class_schedule'] ) );
    // delete_post_meta($post_id, 'class_schedule');
    // foreach($class_schedules as $class_schedule) {
    //     update_post_meta($post_id, 'class_schedule', $class_schedule);
    // }

    //Price
    if(!isset($_POST['class_price'])) {
        $_POST['class_price'] = "";
    }
    $class_price = sanitize_text_field( $_POST['class_price'] );
    update_post_meta( $post_id, 'class_price', $class_price );

    //Fee
    if(!isset($_POST['class_fee'])) {
        $_POST['class_fee'] = "";
    }
    $class_fee = sanitize_text_field( $_POST['class_fee'] );
    update_post_meta( $post_id, 'class_fee', $class_fee );

    //Students
    if(!isset($_POST['class_students'])) {
        $_POST['class_students'] = "";
    }
    $class_students = sanitize_text_field( $_POST['class_students'] );
    update_post_meta( $post_id, 'class_students', $class_students );

    //Gender
    if(!isset($_POST['class_gender'])) {
        $_POST['class_gender'] = "";
    }
    $class_gender = sanitize_text_field( $_POST['class_gender'] );
    update_post_meta( $post_id, 'class_gender', $class_gender );

    //Target
    // $old_targets = get_post_meta($post_id, 'class_target', true);
    // $new_targets = array();

    // $targets = isset($_POST['class_target']) ? $_POST['class_target'] : array();
    // $count_targets = count($targets);

    // for ($i = 0; $i < $count_targets; $i++) {
    //     if ($targets[$i] != '') :
    //         $new_targets[$i] = stripslashes(strip_tags($targets[$i]));
    //     endif;
    // }
    // if (!empty($new_targets) && $new_targets != $old_targets)
    //     update_post_meta($post_id, 'class_target', $new_targets);
    // elseif (empty($new_targets) && $old_targets)
    //     delete_post_meta($post_id, 'class_target', $old_targets);

    if(!isset($_POST['class_target'])) {
        $_POST['class_target'] = "";
    }
    $class_targets = sanitize_text_field( $_POST['class_target'] );
    update_post_meta( $post_id, 'class_target', $class_targets );

    //Address
    if(!isset($_POST['class_address'])) {
        $_POST['class_address'] = "";
    }
    $class_address = sanitize_text_field( $_POST['class_address'] );
    update_post_meta( $post_id, 'class_address', $class_address );

    //Format
    if(!isset($_POST['class_format'])) {
        $_POST['class_format'] = "";
    }
    $class_format = sanitize_text_field( $_POST['class_format'] );
    update_post_meta( $post_id, 'class_format', $class_format );

    // Cap hoc
    if(!isset($_POST['class_caphoc'])) {
        $_POST['class_caphoc'] = "";
    }
    $class_caphoc = sanitize_text_field( $_POST['class_caphoc'] );
    update_post_meta( $post_id, 'class_caphoc', $class_caphoc );

    //Level
    // $old_levels = get_post_meta($post_id, 'class_level', true);
    // $new_levels = array();

    // $levels = isset($_POST['class_level']) ? $_POST['class_level'] : array();
    // $count_levels = count($levels);

    // for ($i = 0; $i < $count_levels; $i++) {
    //     if ($levels[$i] != '') :
    //         $new_levels[$i] = stripslashes(strip_tags($levels[$i]));
    //     endif;
    // }
    // if (!empty($new_levels) && $new_levels != $old_levels)
    //     update_post_meta($post_id, 'class_level', $new_levels);
    // elseif (empty($new_levels) && $old_levels)
    //     delete_post_meta($post_id, 'class_level', $old_levels);
    
    //Note
    if(!isset($_POST['class_note'])) {
        $_POST['class_note'] = "";
    }
    $class_note = sanitize_text_field( $_POST['class_note'] );
    update_post_meta( $post_id, 'class_note', $class_note );

    //Tutor
    if(!isset($_POST['class_tutor'])) {
        $_POST['class_tutor'] = "";
    }
    $class_tutor = sanitize_text_field( $_POST['class_tutor'] );
    update_post_meta( $post_id, 'class_tutor', $class_tutor );

    
    //Cập nhật lại gia sư của lớp
    // global $wpdb;

    // $table_name = $wpdb->prefix . 'gs_user_classrooms';
    // $t = get_post_meta( $post->ID, 'class_tutor', true );
	
	// $wpdb->update( 
    //     $table_name, 
    //     array( 
    //         'status' => 'confirmed',   // string
    //     ), 
    //     array( 
    //         'user_id' => $t,
    //         'classroom_id' => $post,
    //     ), 
    //     array( 
    //         '%s'
    //     ), 
    //     array( '%d', '%d' ) 
    // );
}
add_action( 'save_post', 'gs_post_meta_save' );

?>