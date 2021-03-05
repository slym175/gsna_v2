<?php
if(!class_exists('GS_Profile')) {
    class GS_Profile {

        public function __construct()
        {
            add_action( 'show_user_profile', array($this, 'custom_user_profile_fields'), 10, 1 );
            add_action( 'edit_user_profile', array($this, 'custom_user_profile_fields'), 10, 1 );
            add_action( 'profile_update', array($this, 'custom_user_profile_fields_save'), 10, 1 );
            add_action( 'personal_options_update', array($this, 'custom_user_profile_fields_save') );
            add_action( 'edit_user_profile_update', array($this, 'custom_user_profile_fields_save') );
            add_action( 'user_register', array($this, 'custom_user_profile_fields_save') );

            add_shortcode('profile_view', array($this, 'create_shortcode_profile_view') );
            add_shortcode('edit_profile_view', array($this, 'create_shortcode_edit_profile_view') );
            add_filter( 'get_avatar_url', 'ayecode_get_avatar_url', 10, 3 );
        }

        /**
         * Show custom user profile fields
         * 
         * @param  object $profileuser A WP_User object
         * @return void
         */
        public static function custom_user_profile_fields( $profileuser ) { 
            if(is_admin(  )) {
                if($profileuser->roles['0'] == 'tutor') {?>
                    <h2><?php echo __( 'Thông tin cá nhân', GS_TEXTDOMAIN ); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th>
                                <label><?php echo __( 'Giới tính', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <input type="radio" name="user_gender" id="user_gender_0" value="0" <?= 0 == get_the_author_meta( 'user_gender', $profileuser->ID ) ? 'checked' : '' ?>><label for="user_gender_0"><?= __('Nam', GS_TEXTDOMAIN ) ?></label>
                                <input type="radio" name="user_gender" id="user_gender_1" value="1" <?= 1 == get_the_author_meta( 'user_gender', $profileuser->ID ) ? 'checked' : '' ?>><label for="user_gender_1"><?= __('Nữ', GS_TEXTDOMAIN ) ?></label>
                                <input type="radio" name="user_gender" id="user_gender_2" value="2" <?= 2 == get_the_author_meta( 'user_gender', $profileuser->ID ) ? 'checked' : '' ?>><label for="user_gender_2"><?= __('Khác', GS_TEXTDOMAIN ) ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_phone"><?php echo __( 'Số điện thoại', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <input type="text" name="user_phone" id="user_phone" value="<?php echo esc_attr( get_the_author_meta( 'user_phone', $profileuser->ID ) ); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_birth"><?php echo __( 'Ngày tháng năm sinh', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <input type="date" name="user_birth" id="user_birth" value="<?php echo esc_attr( get_the_author_meta( 'user_birth', $profileuser->ID ) ); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_train_province"><?php echo __( 'Tỉnh/thành (Địa điểm dạy)', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <select name="user_train_province" id="user_train_province" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_train_province', $profileuser->ID ) ); ?>">
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_train_district"><?php echo __( 'Quận/huyện (Địa điểm dạy)', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <select name="user_train_district" id="user_train_district" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_train_district', $profileuser->ID ) ); ?>">
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_address"><?php echo __( 'Địa chỉ hiện tại', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <textarea type="text" rows="5" name="user_address" id="user_address" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'user_address', $profileuser->ID ) ); ?></textarea>
                                <p>số nhà, ngách A, ngõ B, thôn C, xã D</p>
                            </td>
                        </tr>
                    </table>
                    <h2><?php echo __( 'Thông tin gia sư', GS_TEXTDOMAIN ); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th>
                                <label for="user_tutor_exp"><?php echo __( 'Kinh nghiệm đi gia sư và giảng dạy ( chi tiết )', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <textarea type="text" rows="5" name="user_tutor_exp" id="user_tutor_exp" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'user_tutor_exp', $profileuser->ID ) ); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_tutor_awards"><?php echo __( 'Thành tích học tập và dạy học ( chi tiết )', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <textarea type="text" rows="5" name="user_tutor_awards" id="user_tutor_awards" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'user_tutor_awards', $profileuser->ID ) ); ?></textarea>
                            </td>
                        </tr>
                    </table>
                    <h2><?php echo __( 'Hồ sơ chuyên môn', GS_TEXTDOMAIN ); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th>
                                <label for="user_prof_role"><?php echo __( 'Bạn đang là ...', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <?php 
                                    $prof_roles = array_map(function($item) {
                                        return rtrim(ltrim($item, " "), " ");
                                    }, explode(';', get_option('gs_options')['tutor_roles']));
                                ?>
                                <select name="user_prof_role" id="user_prof_role" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_role', $profileuser->ID ) ); ?>">
                                    <?php foreach($prof_roles as $prole) : ?>
                                        <option value="<?= $prole ?>" <?= $prole == get_the_author_meta( 'user_prof_role', $profileuser->ID ) ? "selected" : "" ?>><?= $prole ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_prof_format"><?php echo __( 'Hình thức dạy', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <?php 
                                    $prof_formats = array_map(function($item) {
                                        return rtrim(ltrim($item, " "), " ");
                                    }, explode(';', get_option('gs_options')['tutor_formats']));
                                ?>
                                <select name="user_prof_format" id="user_prof_format" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_format', $profileuser->ID ) ); ?>">
                                    <?php foreach($prof_formats as $pformat) : ?>
                                        <option value="<?= $pformat ?>" <?= $pformat == get_the_author_meta( 'user_prof_format', $profileuser->ID ) ? "selected" : "" ?>><?= $pformat ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_prof_price"><?php echo __( 'Học phí (vnđ/buổi)', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <input type="number" name="user_prof_price" id="user_prof_price" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_price', $profileuser->ID ) ); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_prof_subject"><?php echo __( 'Môn học sẽ dạy', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <?php 
                            $terms = get_terms( array(
                                'taxonomy'      => 'class_subject',
                                'hide_empty'    => false,

                            ) );
                            $subs = get_the_author_meta( 'user_prof_subject', $profileuser->ID );
                            
                            ?>
                            <td class="inline-block">
                                <?php foreach($terms as $key => $term) : ?>
                                    <div class="subjects-checkbox">
                                        <input <?= in_array($term->term_id, is_array($subs) ? $subs : array()) ? "checked" : "" ?> type="checkbox" name="user_prof_subject[]" id="user_prof_subject<?= $term->term_id ?>" value="<?= $term->term_id ?>">
                                        <label for="user_prof_subject<?= $term->term_id ?>"><?= $term->name ?></label>
                                    </div>
                                <?php endforeach ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_prof_classes"><?php echo __( 'Số lớp đã dạy', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <input type="number" name="user_prof_classes" id="useuser_prof_classesr_prof_price" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_classes', $profileuser->ID ) ); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_prof_schedule"><?php echo __( 'Thời gian giảng dạy', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <ul>
                                    <?php 
                                    $user_prof_schedule = get_the_author_meta( 'user_prof_schedule', $profileuser->ID );
                                    if(isset($user_prof_schedule) && is_array($user_prof_schedule)){
                                    foreach($user_prof_schedule as $prof_schedule) {
                                    ?>
                                        <li><?= $prof_schedule['user_schedule_day']." -- ".$prof_schedule['user_schedule_start']." - ".$prof_schedule['user_schedule_end'] ?></li>
                                    <?php }}else{ ?>
                                        <p>Gia sư chưa cập nhật thông tin này</p>
                                    <?php } ?>
                                </ul>
                            </td>
                        </tr>
                    </table>
                    <h2><?php echo __( 'Ảnh hồ sơ', GS_TEXTDOMAIN ); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th>
                                <label for="user_prof_id_card"><?php echo __( 'Ảnh CMT/Căn cước/Hộ chiếu', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <?php 
                                    if( get_field( 'user_prof_id_card', 'user_'. $profileuser->ID ) != "") {
                                        $id_cards = get_field( 'user_prof_id_card', 'user_'. $profileuser->ID );
                                        foreach($id_cards as $card) {
                                        ?>
                                        <img src="<?= $card['url'] ?>" alt="<?= $card['title'] ?>" class="photo" height="96">
                                        <?php
                                        }
                                    }else{
                                        ?>
                                            <p>Gia sư chưa cập nhật thông tin này</p>
                                        <?php
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_prof_certificate"><?php echo __( 'Thẻ sinh viên/bằng/chứng chỉ', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <?php 
                                    if( get_field( 'user_prof_certificate', 'user_'. $profileuser->ID ) != "") {
                                        $id_cards = get_field( 'user_prof_certificate', 'user_'. $profileuser->ID );
                                        foreach($id_cards as $card) {
                                        ?>
                                        <img src="<?= $card['url'] ?>" alt="<?= $card['title'] ?>" class="photo" height="96">
                                        <?php
                                        }
                                    }else{
                                        ?>
                                            <p>Gia sư chưa cập nhật thông tin này</p>
                                        <?php
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="user_prof_activation"><?php echo __( 'Ảnh hoạt động', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <?php 
                                    if( get_field( 'user_prof_activation', 'user_'. $profileuser->ID ) != "") {
                                        $id_cards = get_field( 'user_prof_activation', 'user_'. $profileuser->ID );
                                        foreach($id_cards as $card) {
                                        ?>
                                        <img src="<?= $card['url'] ?>" alt="<?= $card['title'] ?>" class="photo" height="96">
                                        <?php
                                        }
                                    }else{
                                        ?>
                                            <p>Gia sư chưa cập nhật thông tin này</p>
                                        <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <h2><?php echo __( 'Thông tin khác', GS_TEXTDOMAIN ); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th>
                                <label for="user_prof_intro_video"><?php echo __( 'Video giới thiệu', GS_TEXTDOMAIN ); ?></label>
                            </th>
                            <td>
                                <?php 
                                    if(get_the_author_meta( 'user_prof_intro_video', $profileuser->ID ) != "") {
                                        ?>
                                        <iframe width="560" height="315" src="<?php echo esc_attr( get_the_author_meta( 'user_prof_intro_video', $profileuser->ID ) ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <?php
                                    }else{
                                        ?>
                                            <p>Gia sư chưa cập nhật thông tin này</p>
                                        <?php
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
            <?php }} else {
            ?>
                <h2><?php echo __( 'Thông tin cá nhân', GS_TEXTDOMAIN ); ?></h2>
                <p class="form-user_gender">
                    <label><?php echo __( 'Giới tính', GS_TEXTDOMAIN ); ?></label>
                    <span class="display-flex">
                        <input type="radio" name="user_gender" id="user_gender_0" value="0" <?= 0 == get_the_author_meta( 'user_gender', $profileuser->ID ) ? 'checked' : '' ?>><label for="user_gender_0"><?= __('Nam', GS_TEXTDOMAIN ) ?></label>
                        <input type="radio" name="user_gender" id="user_gender_1" value="1" <?= 1 == get_the_author_meta( 'user_gender', $profileuser->ID ) ? 'checked' : '' ?>><label for="user_gender_1"><?= __('Nữ', GS_TEXTDOMAIN ) ?></label>
                        <input type="radio" name="user_gender" id="user_gender_2" value="2" <?= 2 == get_the_author_meta( 'user_gender', $profileuser->ID ) ? 'checked' : '' ?>><label for="user_gender_2"><?= __('Khác', GS_TEXTDOMAIN ) ?></label>
                    </span>
                </p>
                <p class="form-user_phone">
                    <label for="user_phone"><?php echo __( 'Số điện thoại', GS_TEXTDOMAIN ); ?> <span class="required">*</span></label>
                    <input type="text" name="user_phone" id="user_phone" value="<?php echo esc_attr( get_the_author_meta( 'user_phone', $profileuser->ID ) ); ?>" class="regular-text" required />
                </p>
                <p class="form-user_birth">
                    <label for="user_birth"><?php echo __( 'Ngày tháng năm sinh', GS_TEXTDOMAIN ); ?> <span class="required">*</span></label>
                    <input type="date" name="user_birth" id="user_birth" value="<?php echo esc_attr( get_the_author_meta( 'user_birth', $profileuser->ID ) ); ?>" class="regular-text" required />
                </p>
                <p class="form-user_train_province">
                    <label for="user_train_province"><?php echo __( 'Tỉnh/thành (Địa điểm dạy)', GS_TEXTDOMAIN ); ?></label>
                    <select name="user_train_province" id="user_train_province" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_train_province', $profileuser->ID ) ); ?>">
                                
                    </select>
                </p>
                <p class="form-user_train_district">
                    <label for="user_train_district"><?php echo __( 'Quận/huyện (Địa điểm dạy)', GS_TEXTDOMAIN ); ?></label>
                    <select name="user_train_district" id="user_train_district" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_train_district', $profileuser->ID ) ); ?>">
                                
                    </select>
                </p>
                <p class="form-user_address">
                    <label for="user_address"><?php echo __( 'Địa chỉ hiện tại', GS_TEXTDOMAIN ); ?></label>
                    <textarea type="text" rows="5" name="user_address" id="user_address" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'user_address', $profileuser->ID ) ); ?></textarea>
                    <p>số nhà, ngách A, ngõ B, thôn C, xã D</p>
                </p>
                <h2><?php echo __( 'Thông tin gia sư', GS_TEXTDOMAIN ); ?></h2>
                <p>
                    <label for="user_tutor_exp"><?php echo __( 'Kinh nghiệm đi gia sư và giảng dạy ( chi tiết )', GS_TEXTDOMAIN ); ?></label>
                    <textarea type="text" rows="5" name="user_tutor_exp" id="user_tutor_exp" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'user_tutor_exp', $profileuser->ID ) ); ?></textarea>
                </p>
                <p>
                    <label for="user_tutor_awards"><?php echo __( 'Thành tích học tập và dạy học ( chi tiết )', GS_TEXTDOMAIN ); ?></label>
                    <textarea type="text" rows="5" name="user_tutor_awards" id="user_tutor_awards" class="regular-text" ><?php echo esc_attr( get_the_author_meta( 'user_tutor_awards', $profileuser->ID ) ); ?></textarea>
                </p>
                <h2><?php echo __( 'Hồ sơ chuyên môn', GS_TEXTDOMAIN ); ?></h2>
                <p>
                    <label for="user_prof_role"><?php echo __( 'Bạn đang là ...', GS_TEXTDOMAIN ); ?></label>
                    <?php 
                        $prof_roles = array_map(function($item) {
                            return rtrim(ltrim($item, " "), " ");
                        }, explode(';', get_option('gs_options')['tutor_roles']));
                    ?>
                    <select name="user_prof_role" id="user_prof_role" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_role', $profileuser->ID ) ); ?>">
                        <?php foreach($prof_roles as $prole) : ?>
                            <option value="<?= $prole ?>" <?= $prole == get_the_author_meta( 'user_prof_role', $profileuser->ID ) ? "selected" : "" ?>><?= $prole ?></option>
                        <?php endforeach ?>
                    </select>
                </p>
                <p>
                    <label for="user_prof_format"><?php echo __( 'Hình thức dạy', GS_TEXTDOMAIN ); ?></label>
                    <?php 
                        $prof_formats = array_map(function($item) {
                            return rtrim(ltrim($item, " "), " ");
                        }, explode(';', get_option('gs_options')['tutor_formats']));
                    ?>
                    <select name="user_prof_format" id="user_prof_format" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_format', $profileuser->ID ) ); ?>">
                        <?php foreach($prof_formats as $pformat) : ?>
                            <option value="<?= $pformat ?>" <?= $pformat == get_the_author_meta( 'user_prof_format', $profileuser->ID ) ? "selected" : "" ?>><?= $pformat ?></option>
                        <?php endforeach ?>
                    </select>
                </p>
                <p>
                    <label for="user_prof_price"><?php echo __( 'Học phí (vnđ/buổi)', GS_TEXTDOMAIN ); ?></label>
                    <input type="number" name="user_prof_price" id="user_prof_price" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_price', $profileuser->ID ) ); ?>" class="regular-text" />
                </p>
                <p>
                    <label for="user_prof_subject"><?php echo __( 'Môn học sẽ dạy', GS_TEXTDOMAIN ); ?></label>
                    <?php 
                        $terms = get_terms( array(
                            'taxonomy'      => 'class_subject',
                            'hide_empty'    => false,

                        ) );
                        $subs = get_the_author_meta( 'user_prof_subject', $profileuser->ID );  
                    ?>
                    <?php foreach($terms as $key => $term) : ?>
                        <span class="subjects-checkbox">
                            <input <?= in_array($term->term_id, is_array($subs) ? $subs : array()) ? "checked" : "" ?> type="checkbox" name="user_prof_subject[]" id="user_prof_subject<?= $term->term_id ?>" value="<?= $term->term_id ?>">
                            <label for="user_prof_subject<?= $term->term_id ?>"><?= $term->name ?></label>
                        </span>
                    <?php endforeach ?>
                </p>
                <p>
                    <label for="user_prof_classes"><?php echo __( 'Số lớp đã dạy', GS_TEXTDOMAIN ); ?></label>
                    <input type="number" name="user_prof_classes" id="useuser_prof_classesr_prof_price" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_classes', $profileuser->ID ) ); ?>" class="regular-text" />
                </p>
                <p>
                    <label for="user_prof_schedule"><?php echo __( 'Thời gian giảng dạy', GS_TEXTDOMAIN ); ?></label>
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
                            $repeatable_fields = get_the_author_meta('user_prof_schedule', $profileuser->ID);
                            wp_nonce_field('hhs_repeatable_meta_box_nonce', 'hhs_repeatable_meta_box_nonce'); 
                            $days_of_week = array('Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật');
                        ?>
                        <?php if ($repeatable_fields) :
                            foreach ($repeatable_fields as $field) : ?>
                                <tr class="form-row">
                                    <td>
                                        <a href="javascript:void(0)" onclick="insertRepeaterRow(this)"><i class="dashicons-before dashicons-insert"></i></a>
                                    </td>
                                    <td class="width: 50%">
                                        <select class="regular-text form-control class_schedule_day" name="user_schedule_day[]" value="<?php echo $field['user_schedule_day'] ?>">
                                            <?php foreach($days_of_week as $key => $day) : ?>
                                                <option value="<?= $day ?>"<?= $day == $field['user_schedule_day'] ? "selected" : ""?>><?= $day ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="regular-text form-control class_schedule_start" type="time" name="user_schedule_start[]" value="<?php echo $field['user_schedule_start'] ?>">
                                    </td>
                                    <td>
                                        <input class="regular-text form-control class_schedule_end" type="time" name="user_schedule_end[]" value="<?php echo $field['user_schedule_end'] ?>">
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="removeRepeaterRow(this)"><i class="dashicons-before dashicons-dismiss"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr class="form-row">
                                <td>
                                    <a href="javascript:void(0)" onclick="insertRepeaterRow(this)"><i class="dashicons-before dashicons-insert"></i></a>
                                </td>
                                <td class="width: 50%">
                                    <select class="regular-text form-control class_schedule_day" name="user_schedule_day[]">
                                        <?php foreach($days_of_week as $key => $day) : ?>
                                            <option value="<?= $day ?>"><?= $day ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td>
                                    <input class="regular-text form-control class_schedule_start" type="time" name="user_schedule_start[]">
                                </td>
                                <td>
                                    <input class="regular-text form-control class_schedule_end" type="time" name="user_schedule_end[]">
                                </td>
                                <td>
                                    <a href="javascript:void(0)" onclick="removeRepeaterRow(this)"><i class="dashicons-before dashicons-dismiss"></i></a>
                                </td>
                            </tr>
                        <?php endif ?>
                    </table>
                </p>
                
                <h2><?php echo __( 'Thông tin khác', GS_TEXTDOMAIN ); ?></h2>
                <p>
                    <label for="user_prof_intro_video"><?php echo __( 'Video giới thiệu', GS_TEXTDOMAIN ); ?></label>
                    <input type="text" name="user_prof_intro_video" id="user_prof_intro_video" value="<?php echo esc_attr( get_the_author_meta( 'user_prof_intro_video', $profileuser->ID ) ); ?>" class="regular-text" />
                    <?php if(get_the_author_meta( 'user_prof_intro_video', $profileuser->ID ) != "") { ?>
                        <iframe width="560" height="315" src="<?php echo esc_attr( get_the_author_meta( 'user_prof_intro_video', $profileuser->ID ) ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    <?php }else{ ?>
                        <p>Gia sư chưa cập nhật thông tin này</p>
                    <?php } ?>
                </p>
            <?php
        }}

        public static function custom_user_profile_fields_save($userId) {
            if (!current_user_can('edit_user', $userId)) {
                return;
            }
                    
            update_user_meta($userId, 'user_gender', $_REQUEST['user_gender']);
            update_user_meta($userId, 'user_phone', $_REQUEST['user_phone']);
            update_user_meta($userId, 'user_birth', $_REQUEST['user_birth']);
            update_user_meta($userId, 'user_train_province', $_REQUEST['user_train_province']);
            update_user_meta($userId, 'user_train_district', $_REQUEST['user_train_district']);
            update_user_meta($userId, 'user_address', $_REQUEST['user_address']);

            update_user_meta($userId, 'user_tutor_exp', $_REQUEST['user_tutor_exp']);
            update_user_meta($userId, 'user_tutor_awards', $_REQUEST['user_tutor_awards']);

            update_user_meta($userId, 'user_prof_role', $_REQUEST['user_prof_role']);
            update_user_meta($userId, 'user_prof_format', $_REQUEST['user_prof_format']);
            update_user_meta($userId, 'user_prof_price', $_REQUEST['user_prof_price']);
            update_user_meta($userId, 'user_prof_subject', $_REQUEST['user_prof_subject']);
            update_user_meta($userId, 'user_prof_classes', $_REQUEST['user_prof_classes']);
            update_user_meta($userId, 'user_prof_schedule', $_REQUEST['user_prof_schedule']);

            $old = get_the_author_meta( 'user_prof_schedule', $post_id );
            $new = array();

            $schedule = isset($_POST['user_schedule_day']) ? $_POST['user_schedule_day'] : "";
            $start_date = isset($_POST['user_schedule_start']) ? $_POST['user_schedule_start'] : "";
            $end_date = isset($_POST['user_schedule_end']) ? $_POST['user_schedule_end'] : "";

            $count = count($schedule);

            for ($i = 0; $i < $count; $i++) {
                if ($schedule[$i] != '') :
                    $new[$i]['user_schedule_day'] = stripslashes(strip_tags($schedule[$i]));
                    $new[$i]['user_schedule_start'] = stripslashes(strip_tags($start_date[$i]));
                    $new[$i]['user_schedule_end'] = stripslashes(strip_tags($end_date[$i]));
                endif;
            }

            if (!empty($new) && $new != $old)
                update_user_meta($userId, 'user_prof_schedule', $new);
            elseif (empty($new) && $old)
                delete_user_meta($userId, 'user_prof_schedule', $old);


            // add_filter( 'avatar_defaults', array($this, 'wpb_new_gravatar'));

            update_user_meta($userId, 'user_prof_intro_video', $_REQUEST['user_prof_intro_video']);
        }

        public static function create_shortcode_profile_view() {
            require_once GS_PLUGIN_DIR . "/public/views/profiles/index.php";
        }

        public static function create_shortcode_edit_profile_view() {
            require_once GS_PLUGIN_DIR . "/public/views/profiles/edit.php";
        }

    }

    function ayecode_get_avatar_url( $url, $id_or_email, $args ) {
        $id = '';
        if ( is_numeric( $id_or_email ) ) {
            $id = (int) $id_or_email;
        } elseif ( is_object( $id_or_email ) ) {
            if ( ! empty( $id_or_email->user_id ) ) {
                $id = (int) $id_or_email->user_id;
            }
        } else {
            $user = get_user_by( 'email', $id_or_email );
            $id = !empty( $user ) ?  $user->data->ID : '';
        }
        //Preparing for the launch.
        $custom_url = $id ?  get_field( 'user_prof_avatar','user_'. $id ) : '';
        
        // If there is no custom avatar set, return the normal one.
        if( $custom_url == '' || !empty($args['force_default'])) {
            return esc_url_raw( 'https://secure.gravatar.com/avatar/9ff1f2e96da0ba90b50b3d0ec986141a?s=96&d=mm&r=g' ); 
        }else{
            return esc_url_raw($custom_url);
        }
    }

    new GS_Profile();
}