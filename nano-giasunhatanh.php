<?php 
/**
 * Plugin Name:       GiaSuNhatAnh Classroom
 * Plugin URI:        http://nanoweb.vn/
 * Description:       Quản trị khóa học, gia sư
 * Version:           1.2.0
 * Author:            NanoWeb && thuyhu9876@gmail.com
 * Author URI:        http://nanoweb.vn/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gsna-classroom
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) or exit;

define( 'GS_TEXTDOMAIN', 'gsna-classroom' );

if ( ! defined( 'GS_PLUGIN_FILE' ) ) {
    define( 'GS_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'GS_PLUGIN_DIR' ) ) {
    define( 'GS_PLUGIN_DIR', dirname(__FILE__));
}

if ( ! defined( 'GS_PLUGIN_DIR_ROOT' ) ) {
    define( 'GS_PLUGIN_DIR_ROOT', plugins_url ().'/'.basename(dirname(__FILE__)));
}

if ( ! defined( 'GS_STATUS' ) ) {
    define( 'GS_STATUS', array(
        'confirmed' => __('Confirmed', GS_TEXTDOMAIN),
        'pending'   => __('Pending', GS_TEXTDOMAIN)
    ));
}

if ( ! defined( 'GS_UPLOAD_DIR' ) ) {
    define( 'GS_UPLOAD_DIR', wp_upload_dir()['basedir'].'/gsna-dir');
}

if(!class_exists('GiaSuNhatAnh')) {
    add_action('plugins_loaded', array('GiaSuNhatAnh', 'init'));
    class GiaSuNhatAnh {

        protected static $instance;


        public function __construct()
        {

            $upload = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $upload_dir = $upload_dir . '/gsna-dir';
            if (! is_dir($upload_dir)) {
                mkdir( $upload_dir, 0700 );
            }

            add_role( 'tutor', __('Gia sư', GS_TEXTDOMAIN), get_role( 'customer' )->capabilities );
            update_option( 'woocommerce_registration_generate_username', 'no');
            update_option( 'woocommerce_registration_generate_password', 'no');
            add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );

            require_once GS_PLUGIN_DIR . "/admin/class_classroom_type.php";
            require_once GS_PLUGIN_DIR . "/includes/helpers.php";

            if(is_admin(  )) {
                add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_loader') );
                add_action('admin_menu', array( $this, 'add_admin_plugin_setting_menu' ), 9);
                require_once GS_PLUGIN_DIR . "/includes/settings.php";
            }

            add_action('user_register', array($this, 'user_register_function'), 10, 1);
            add_action( 'wp_enqueue_scripts', array($this, 'public_enqueue_scripts_loader') );
            add_filter( 'template_include', 'single_classroom_templates' );

            // remove_menu_page('woocommerce');

            require_once GS_PLUGIN_DIR . "/public/templates_loader.php";
            require_once GS_PLUGIN_DIR . "/public/custom_user_profile.php";
            require_once GS_PLUGIN_DIR . "/public/classroom_button.php";
            require_once GS_PLUGIN_DIR . "/public/classroom_filter.php";
            
        }

        public static function init()
        {
            is_null(self::$instance) AND self::$instance = new self;
            return self::$instance;
        }

        public static function add_admin_plugin_setting_menu() {
    
            //add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
            $hook = add_submenu_page( 'edit.php?post_type=classroom', __('Quản lý nhận lớp', GS_TEXTDOMAIN), __('Quản lý nhận lớp', GS_TEXTDOMAIN), 'administrator', 'classroom-managements', 'display_classroom_managements');
            // add_submenu_page( 'edit.php?post_type=classroom', __('Cài đặt', GS_TEXTDOMAIN), __('Cài đặt', GS_TEXTDOMAIN), 'administrator', 'classroom-settings', 'display_classroom_settings');

            add_action("load-".$hook, 'mp_custom_screen_options');
        }

        public static function admin_enqueue_scripts_loader($hook)
        {

            wp_register_style( 'gs-select2', GS_PLUGIN_DIR_ROOT . '/admin/css/select2.min.css', array() );
            wp_enqueue_style( 'gs-select2' );
            wp_register_style( 'gs-custom', GS_PLUGIN_DIR_ROOT . '/admin/css/custom.css', array() );
            wp_enqueue_style( 'gs-custom' );
            wp_register_script('gs-settings', GS_PLUGIN_DIR_ROOT . '/admin/js/settings.js', 1, true);
            wp_enqueue_script('gs-settings');
            // $array_settings = array(
            //     'subjects' => get_subjects_select2(),
            // );
            // wp_localize_script('gs-settings', 'data', $array_settings);

            wp_register_script('gs-select2', GS_PLUGIN_DIR_ROOT . '/admin/js/select2.min.js', 1, true);
            wp_enqueue_script('gs-select2');

            wp_register_script('gs-locations', GS_PLUGIN_DIR_ROOT . '/admin/js/locations.js', 1, true);
            wp_enqueue_script('gs-locations');
            $array = array(
                'local_address' => get_local_checkout_json(),
                'select_text' => 'Chọn quận/Huyện',
                'ajaxurl' => admin_url('admin-ajax.php'),
            );
            wp_localize_script('gs-locations', 'local_array', $array);

            wp_register_script('gs-repeater', GS_PLUGIN_DIR_ROOT . '/admin/js/repeater.js', 1, true);
            wp_enqueue_script('gs-repeater');
            $array = array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            );
            wp_localize_script('gs-repeater', 'data_array', $array);
        }
    
        public static function public_enqueue_scripts_loader()
        {

            wp_register_style( 'gs-fontawesome', GS_PLUGIN_DIR_ROOT . '/admin/css/all.min.css', array() );
            wp_enqueue_style( 'gs-fontawesome' );
            wp_register_style( 'gs-select2', GS_PLUGIN_DIR_ROOT . '/admin/css/select2.min.css', array() );
            wp_enqueue_style( 'gs-select2' );
            wp_register_style( 'gs-custom', GS_PLUGIN_DIR_ROOT . '/admin/css/custom.css', array() );
            wp_enqueue_style( 'gs-custom' );

            wp_register_script('gs-jquery', GS_PLUGIN_DIR_ROOT . '/public/js/jquery-3.5.1.min.js', 1, true);
            wp_enqueue_script('gs-jquery');

            wp_register_script('gs-fontawesome', GS_PLUGIN_DIR_ROOT . '/public/js/all.min.js', 1, true);
            wp_enqueue_script('gs-fontawesome');

            wp_register_script('gs-select2', GS_PLUGIN_DIR_ROOT . '/admin/js/select2.min.js', 1, true);
            wp_enqueue_script('gs-select2');

            $array = array(
                'ajax_url'      => admin_url('admin-ajax.php'),
                'local_address' => get_local_checkout_json(),
                'subjects'      => get_subjects(),
                'caphoc'        => get_caphoc(),
                'targets'       => get_targets(),
                'formats'       => get_format()
            );
            wp_register_script('gs-send-class-request', GS_PLUGIN_DIR_ROOT . '/public/js/sendclassrequest.js', 1, true);
            wp_enqueue_script('gs-send-class-request');
            wp_localize_script('gs-send-class-request', 'request_data', $array);

            wp_register_script('gs-locations', GS_PLUGIN_DIR_ROOT . '/admin/js/locations.js', 1, true);
            wp_enqueue_script('gs-locations');
            $array = array(
                'local_address' => get_local_checkout_json(),
                'select_text' => 'Chọn quận/Huyện',
                'ajaxurl' => admin_url('admin-ajax.php'),
            );
            wp_localize_script('gs-locations', 'local_array', $array);    
            
            wp_register_script('gs-repeater', GS_PLUGIN_DIR_ROOT . '/admin/js/repeater.js', 1, true);
            wp_enqueue_script('gs-repeater');
            $array = array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            );
            wp_localize_script('gs-repeater', 'data_array', $array);
        }

        public static function user_register_function( $user_id ) {
            if ( isset($_POST['user_role']) && $_POST['user_role'] == 'tutor') 
            {
                $useridid = get_userdata( $user_id );
                $role_to_add = 'tutor';
                $useridid->remove_role('customer');
                $useridid->add_role($role_to_add);
            }
        }
        
    }

    register_activation_hook( GS_PLUGIN_FILE, 'gs_create_database' );
}

function mp_custom_screen_options()
{
    $options = 'custom_per_page_options';
         
    $args = array(
        'label'     => 'Per Page',
        'default'   => 20,
        'option'    => 'submenudata_per_page'
    );
    add_screen_option($options, $args);
}

function gs_create_database()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix."gs_user_classrooms";
    // $table_user = $wpdb->prefix."users";
    // $table_class = $wpdb->prefix."p";

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        user_name varchar(255) NOT NULL,
        user_phone varchar(255) NOT NULL,
        user_email varchar(255) NOT NULL,
        classroom_id mediumint(9) NOT NULL,
        classroom_name varchar(255) NOT NULL,
        classroom_code varchar(50) NOT NULL,
        status tinytext NOT NULL,
        is_seen tinyint DEFAULT 0,
        created_at datetime DEFAULT '0000-00-00 00:00:00',
        updated_at datetime DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (id)
        -- FOREIGN KEY (user_id) REFERENCES $table_user(ID),
        -- FOREIGN KEY (classroom_id) REFERENCES $table_class(ID)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function misha_remove_my_account_links( $menu_links ){
 
	unset( $menu_links['edit-address'] ); // Addresses
	//unset( $menu_links['dashboard'] ); // Remove Dashboard
	//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	unset( $menu_links['orders'] ); // Remove Orders
	unset( $menu_links['downloads'] ); // Disable Downloads
	//unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link
 
	return $menu_links;
 
}


function single_classroom_templates( $template ) {
    $post_types = array( 'classroom' );

    // if ( is_post_type_archive( $post_types ) ){
    //     $template = plugin_dir_path(__FILE__) . 'templates/archive_help_lessions.php';
    // }

    if ( is_singular( $post_types ) && file_exists( plugin_dir_path(__FILE__) . "public/single-classroom.php" ) ){
        $template = plugin_dir_path(__FILE__) . 'public/single-classroom.php';
    }

    return $template;
}