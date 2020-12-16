<?php 

if(!class_exists('GCTemplateLoader')) {
    class GCTemplateLoader{
 
        public function __construct()
        {
            add_filter( 'page_template', array($this, 'gs_login_template') );
            add_filter( 'page_template', array($this, 'gs_register_template') );
            add_filter( 'page_template', array($this, 'gs_classroom_template') );
            add_filter( 'page_template', array($this, 'gs_profile_template') );
            add_filter( 'theme_page_templates', array($this, 'gs_add_template_to_select'), 10, 4 );
        }

        //Load template from specific page
        public static function gs_login_template( $page_template ){

            if ( get_page_template_slug() == 'template-login.php' ) {
                $page_template = GS_PLUGIN_DIR . "/public/template-login.php";
            }
            return $page_template;
        }

        public static function gs_register_template( $page_template ){

            if ( get_page_template_slug() == 'template-register.php' ) {
                $page_template = GS_PLUGIN_DIR . "/public/template-register.php";
            }
            return $page_template;
        }

        public static function gs_classroom_template( $page_template ){

            if ( get_page_template_slug() == 'template-classroom.php' ) {
                $page_template = GS_PLUGIN_DIR . "/public/template-classroom.php";
            }
            return $page_template;
        }

        public static function gs_profile_template( $page_template ){

            if ( get_page_template_slug() == 'template-profile.php' ) {
                $page_template = GS_PLUGIN_DIR . "/public/template-profile.php";
            }
            return $page_template;
        }

        /**
         * Add template to page attirbute template section.
         */
        public static function gs_add_template_to_select( $post_templates, $wp_theme, $post, $post_type ) {

            // Add template named template-name.php to select dropdown 
            $post_templates['template-login.php'] = __('Login');
            $post_templates['template-register.php'] = __('Register');
            $post_templates['template-classroom.php'] = __('Classroom');
            $post_templates['template-profile.php'] = __('Profile');

            return $post_templates;
        }

    }
}

new GCTemplateLoader();

function wp_login_form_custom( $args = array() ) {
    $defaults = array(
        'echo'           => true,
        'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'form_id'        => 'loginform',
        'label_username' => __( 'Username or Email Address' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in'   => __( 'Log In' ),
        'id_username'    => 'user_login',
        'id_password'    => 'user_pass',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'remember'       => true,
        'value_username' => '',
        'value_remember' => false,
    );

    $args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
    $form = '<form class="login-acc" name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post"
				<span><input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input" value="' . esc_attr( $args['value_username'] ) . '" size="20" placeholder="Tên tài khoản" /><img src="<?= THEME_URL_URI ?>/assets/img/83.png" alt=""> </span>
				<span><input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input" value="" size="20" placeholder="Mật khẩu" /><img src="<?= THEME_URL_URI ?>/assets/img/84.png" alt=""> </span>
				<div class="check-box">
					<span><input type="checkbox" placeholder=" Email"> Ghi nhớ mật khẩu </span>
					<a href="" title="">Quên mật khẩu?</a>
				</div>
				<button type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button button-primary btn-login" value="' . esc_attr( $args['label_log_in'] ) . '">Đăng nhập</button>
            </form>';

    if ( $args['echo'] ) {
        echo $form;
    } else {
        return $form;
    }
}

function registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $role ) {
    $form = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                <span>
                    <input type="radio" id="gs_tutor" name="role" value="tutor" placeholder="'.__('Gia sư', GS_TEXTDOMAIN).'"> 
                    <label for="gs_tutor">'.__('Gia sư', GS_TEXTDOMAIN).'</label><br>
                    <input type="radio" id="gs_student" name="role" value="subscriber" placeholder="'.__('Học viên', GS_TEXTDOMAIN).'">
                    <label for="gs_student">'.__('Học viên', GS_TEXTDOMAIN).'</label><br>
                </span>
                <span><input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '" placeholder="Tên tài khoản"> </span>
                <span><input type="text" placeholder="Số điện thoại"></span>
                <span><input type="email" name="email" value="' . ( isset( $_POST['email'] ) ? $email : null ) . '" placeholder="Email"></span>
                <span><input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '" placeholder="Mật khẩu" id="password"></span>
                <span><input type="password" placeholder=" Nhắc lại mật khẩu "></span>
                
                <input type="submit" class="btn btn-login" name="submit" value="Đăng ký" id="register" />
            </form>';
    echo $form;
}

function registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio , $role)
{
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
        $reg_errors->add('field', 'Required form field is missing');
    }
    if ( 4 > strlen( $username ) ) {
        $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
    }
    if ( username_exists( $username ) ){
        $reg_errors->add('user_name', 'Sorry, that username already exists!');
    }
    if ( ! validate_username( $username ) ) {
        $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
    }
    if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Password length must be greater than 5' );
    }
    if ( email_exists( $email ) ) {
        $reg_errors->add( 'email', 'Email Already in use' );
    }
    if ( ! empty( $website ) ) {
        if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
            $reg_errors->add( 'website', 'Website is not a valid URL' );
        }
    }
    if ( is_wp_error( $reg_errors ) ) {

        foreach ( $reg_errors->get_error_messages() as $error ) {

            echo '<p class="login-false">';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';
            echo '</p>';

        }

    }
}

function complete_registration() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $role;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
            'user_login'    =>   $username,
            'user_email'    =>   $email,
            'user_pass'     =>   $password,
            'user_url'      =>   $website,
            'first_name'    =>   $first_name,
            'last_name'     =>   $last_name,
            'nickname'      =>   $nickname,
            'description'   =>   $bio,
            'role'          =>   $role
        );
        $user = wp_insert_user( $userdata );
    }
}

function custom_registration_function() {
    global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $role;

    if ( isset($_POST['submit'] ) ) {
        registration_validation(
            $_POST['username'],
            $_POST['password'],
            $_POST['email'],
            '',
            '',
            '',
            '',
            '',
            $_POST['role']
        );

        // sanitize user form input
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $website    =   '';
        $first_name =   '';
        $last_name  =   '';
        $nickname   =   '';
        $bio        =   '';
        $role       =   esc_attr( $_POST['role'] );

        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
            $username,
            $password,
            $email,
            $website,
            $first_name,
            $last_name,
            $nickname,
            $bio,
            $role
        );
    }

    registration_form(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio,
        $role
    );
}
