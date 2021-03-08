<?php 
/**
 * Plugin Name:       GiaSuNhatAnh Classroom
 * Plugin URI:        http://nanoweb.vn/
 * Description:       Quản trị khóa học, gia sư
 * Version:           1.2.1
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
        'confirmed' => __('Đã xác nhận', GS_TEXTDOMAIN),
        'pending'   => __('Chờ xác nhận', GS_TEXTDOMAIN)
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

            if ( ! class_exists( 'WP_Async_Request' ) ) {
                require_once GS_PLUGIN_DIR . '/admin/noty/wp-async-request.php';
            }
            if ( ! class_exists( 'WP_Background_Process' ) ) {
                require_once GS_PLUGIN_DIR . '/admin/noty/wp-background-process.php';
            }

            $upload = wp_upload_dir();
            $upload_dir = $upload['basedir'];
            $upload_dir = $upload_dir . '/gsna-dir';
            if (! is_dir($upload_dir)) {
                mkdir( $upload_dir, 0700 );
            }

            add_role( 'tutor', __('Gia sư', GS_TEXTDOMAIN), get_role( 'customer' )->capabilities );
            $tutor = get_role('tutor');
            $tutor->add_cap('upload_files');
            add_action('after_setup_theme', array( $this, 'remove_admin_bar' ));
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

            if(!is_admin()) {
                add_action( 'admin_footer-post-new.php', 'cc_media_default' );
                add_action( 'admin_footer-post.php', 'cc_media_default' );
            }
            require_once GS_PLUGIN_DIR . "/public/templates_loader.php";
            require_once GS_PLUGIN_DIR . "/public/custom_user_profile.php";
            require_once GS_PLUGIN_DIR . "/public/classroom_button.php";
            require_once GS_PLUGIN_DIR . "/public/classroom_filter.php";

            require_once GS_PLUGIN_DIR . "/admin/noty/class_classroom_notify_helper.php";
            require_once GS_PLUGIN_DIR . "/admin/noty/noty_sender.php";
        }

        public static function init()
        {
            is_null(self::$instance) AND self::$instance = new self;
            return self::$instance;
        }

        public static function add_admin_plugin_setting_menu() {
            $c_id = get_current_user_id();
            $user = new WP_User($c_id);
            $u_role =  $user->roles;

            if(in_array('editor', $u_role) ) {
                $hook = add_submenu_page( 
                    'edit.php?post_type=classroom', 
                    __('Quản lý nhận lớp', GS_TEXTDOMAIN), 
                    get_unseen_registration_classroom_count() != 0 ? sprintf( 'Quản lý nhận lớp <span class="awaiting-mod">%1$d</span>', get_unseen_registration_classroom_count() ) : __('Quản lý nhận lớp', GS_TEXTDOMAIN),
                    'editor', 
                    'classroom-managements', 
                    'display_classroom_managements'
                );
            }else{
                $hook = add_submenu_page( 
                    'edit.php?post_type=classroom', 
                    __('Quản lý nhận lớp', GS_TEXTDOMAIN), 
                    get_unseen_registration_classroom_count() != 0 ? sprintf( 'Quản lý nhận lớp <span class="awaiting-mod">%1$d</span>', get_unseen_registration_classroom_count() ) : __('Quản lý nhận lớp', GS_TEXTDOMAIN),
                    'administrator', 
                    'classroom-managements', 
                    'display_classroom_managements'
                );
            }
            
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
            if ( isset($_POST['phone']) ) 
            {
                update_user_meta($user_id, 'user_phone', $_POST['phone']);
            }
        }

        public static function remove_admin_bar() {
            if (!current_user_can('administrator') && !is_admin()) {
                show_admin_bar(false);
            }
        }
        
    }

    register_activation_hook( GS_PLUGIN_FILE, 'gs_create_database' );
}


function mp_custom_screen_options()
{
    $options = 'per_page';
         
    $args = array(
        'label'     => 'Per Page',
        'default'   => 10,
        'option'    => 'submenudata_per_page'
    );
    add_screen_option($options, $args);
    add_filter('set-screen-option', 'cmi_set_option', 10, 3);
}

function cmi_set_option($status, $option, $value) {
 
    if ( 'submenudata_per_page' == $option ) return $value;
 
    return $status;
 
}

function cc_media_default() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){ wp.media.controller.Library.prototype.defaults.contentUserSetting=false; });
	</script>
	<?php
}

function gs_create_database()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix."gs_user_classrooms";

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
	unset( $menu_links['dashboard'] ); // Remove Dashboard
	unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	unset( $menu_links['orders'] ); // Remove Orders
	unset( $menu_links['downloads'] ); // Disable Downloads
	//unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link
 
	return $menu_links;
 
}

function my_acf_update_field( $field ) {
    $field['uploader'] = 'basic';
    return $field;
}

function single_classroom_templates( $template ) {
    $post_types = array( 'classroom' );


    if ( is_singular( $post_types ) && file_exists( plugin_dir_path(__FILE__) . "public/single-classroom.php" ) ){
        $template = plugin_dir_path(__FILE__) . 'public/single-classroom.php';
    }

    return $template;
}

function my_acf_ungallery_button() { ?>
    <script>
        (function($) {
            window.addEventListener('DOMContentLoaded', function(){
                $('.acf-field-gallery[data-name="user_prof_id_card"] a.acf-gallery-add').text('Thêm ảnh');
                $('.acf-field-gallery[data-name="user_prof_certificate"] a.acf-gallery-add').text('Thêm ảnh');
                $('.acf-field-gallery[data-name="user_prof_id_card"] a.acf-gallery-add').text('Thêm ảnh');
            });
        })(jQuery);
    </script>
<?php }
add_action('acf/input/admin_footer', 'my_acf_ungallery_button');

function get_flatsome_blog_breadcrumbs() {
    $delimiter = '<span class="divider">&#187;</span>';
    $home = 'Trang chủ';
    $before = '';
    $after = '';
    if ( !is_home() && !is_front_page() || is_paged() ) {
        echo '<div class="page-title shop-page-title product-page-title"><div class="page-title-inner flex-row medium-flex-wrap container"><div class="flex-col flex-grow medium-text-center"><div class="is-medium">';
        echo '<nav class="breadcrumbs">';
        global $post;
        $homeLink = get_bloginfo('url');
        echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
        if ( is_category() ) {
            global $wp_query;
            $cat_obj = $wp_query->get_queried_object();
            $thisCat = $cat_obj->term_id;
            $thisCat = get_category($thisCat);
            $parentCat = get_category($thisCat->parent);
            if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
            echo $before . single_cat_title('', false) . $after;
        } elseif ( is_day() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('d') . $after;
        } elseif ( is_month() ) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time('F') . $after;
        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;
        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
                echo $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo $before . get_the_title() . $after;
            }
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;
        } elseif ( is_attachment() ) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && !$post->post_parent ) {
            echo $before . get_the_title() . $after;
        } elseif ( is_page() && $post->post_parent ) {
            $parent_id = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } elseif ( is_search() ) {
            echo $before . 'Search results for "' . get_search_query() . '"' . $after;
        } elseif ( is_tag() ) {
            echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
        } elseif ( is_author() ) {
            global $author;
            echo $before . 'Articles posted by ' . $userdata->display_name . $after;
        } elseif ( is_404() ) {
            echo $before . 'Error 404' . $after;
        }
        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }
        echo '</nav>';
        echo '</div></div></div></div>';
    }
}

// function title_filter($where, &$wp_query){
//     global $wpdb;

//     if($search_term = $wp_query->get( 'classroom_title' )){
//         /*using the esc_like() in here instead of other esc_sql()*/
//         $search_term = $wpdb->esc_like($search_term);
//         $search_term = ' \'%' . $search_term . '%\'';
//         $where .= ' AND ' . $wpdb->posts . '.post_title LIKE '.$search_term;
//     }

//     return $where;
// }

add_filter( 'user_contactmethods', 'newfields', 10, 2);
function newfields( $methods, $user ) {
    if($user->ID != 0 && in_array( 'tutor', $user->roles, true )) {
        unset($methods['facebook']);
        unset($methods['instagram']);
        unset($methods['linkedin']);
        unset($methods['myspace']);
        unset($methods['pinterest']);
        unset($methods['soundcloud']);
        unset($methods['tumblr']);
        unset($methods['twitter']);
        unset($methods['youtube']);
        unset($methods['wikipedia']);
    }
    return $methods;
}

function remove_website_row_wpse_94963_css()
{
    $user_roles = array();
    if(isset($_REQUEST['user_id'])) {
        $user_meta = get_userdata($_REQUEST['user_id']);
        $user_roles = $user_meta->roles;
    }

    if(wp_get_current_user( )->ID != 0 && (in_array( 'tutor', wp_get_current_user( )->roles, true ) || in_array( 'tutor', $user_roles, true )) ) {
        echo '<style>tr.user-url-wrap,tr.user-facebook-wrap,tr.user-instagram-wrap,tr.user-linkedin-wrap,tr.user-myspace-wrap,tr.user-pinterest-wrap,tr.user-soundcloud-wrap,tr.user-tumblr-wrap,tr.user-twitter-wrap,tr.user-youtube-wrap,tr.user-wikipedia-wrap { display: none; }</style>';
    }
}
add_action( 'admin_head-user-edit.php', 'remove_website_row_wpse_94963_css' );
add_action( 'admin_head-profile.php',   'remove_website_row_wpse_94963_css' );

add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );

/**
 * Do stuff only when posts are actually transitioned from one status to another.
 *
 * @param string  $new_status New post status.
 * @param string  $old_status Old post status.
 * @param WP_Post $post       Post object.
 */
function wpdocs_run_on_transition_only( $new_status, $old_status, $post ) {
    // Kiểm tra xem trạng thái của lớp học
    if ( ( $new_status == 'publish' ) && ( $old_status != 'publish' ) && ( $post->post_type == 'classroom' ) ) {
        $classnoty = ClassroomNotyHelper::getInstance();
        $classnoty->setClassroomID($post->ID);
        $invalid_tutors = $classnoty->getTutors();
        write_log($invalid_tutors);

        // $noty = Noty_Sender::getInstance();
        // $noty->sendMail($post->ID, $invalid_tutors);
    } else {
        return;
    }
}
add_action( 'transition_post_status', 'wpdocs_run_on_transition_only', 10, 3 );