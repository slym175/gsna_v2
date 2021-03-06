<?php

if(!class_exists("GsSettingsPage")) {
    class GsSettingsPage
    {
        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Start up
         */
        public function __construct()
        {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
        }

        /**
         * Add options page
         */
        public function add_plugin_page()
        {
            // This page will be under "Settings"
            add_options_page(
                'GS Settings', 
                'GS Settings', 
                'manage_options', 
                'gs-settings', 
                array( $this, 'create_plugin_settings_page' )
            );
        }

        /**
         * Options page callback
         */
        public function create_plugin_settings_page()
        {
            // Set class property
            $this->options = get_option( 'gs_options' );
            ?>
            <div class="wrap">
                <form method="post" action="options.php">
                <?php
                    // This prints out all hidden setting fields
                    settings_fields( 'gs_option_group' );
                    do_settings_sections( 'gs-settings' );
                    submit_button();
                ?>
                </form>
            </div>
            <?php
        }

        /**
         * Register and add settings
         */
        public function page_init()
        {        
            register_setting(
                'gs_option_group', // Option group
                'gs_options', // Option name
                array() // Sanitize
            );

            add_settings_section(
                'gs_setting_section', // ID
                'Cài đặt chung', // Title
                '', // Callback
                'gs-settings' // Page
            );  

            add_settings_field(
                'tutor_roles', // ID
                'Công việc hiện tại', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'tutor_roles', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'tutor_roles', // Field Name
                    'description'   => __('Mỗi công việc viết cách nhau 1 dấu ";"', GS_TEXTDOMAIN),
                    'options'       => array(
                        'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'tutor_formats', // ID
                'Hình thức dạy', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'tutor_formats', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'tutor_formats', // Field Name
                    'description'   => __('Mỗi hình thức viết cách nhau 1 dấu ";"', GS_TEXTDOMAIN),
                    'options'       => array(
                        'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'tutor_targets', // ID
                'Đối tượng giảng dạy', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'tutor_targets', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'tutor_targets', // Field Name
                    'description'   => __('Mỗi đối viết cách nhau 1 dấu ";"', GS_TEXTDOMAIN),
                    'options'       => array(
                        'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'classes_per_page', // ID
                'Số bài viết/trang', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'classes_per_page', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'input', // Field Type
                    'subtype'       => 'number', // Field Subtype
                    'name'          => 'classes_per_page', // Field Name
                    'description'   => __('Mỗi đối tượng viết cách nhau 1 dấu ";"', GS_TEXTDOMAIN),
                    'options'       => array(
                        'required'  => true,
                        'min'       => 1,
                        'step'      => 1
                    )
                ) // Callback Arguments          
            );

            // add_settings_field(
            //     'class_levels', // ID
            //     'Trình độ', // Title 
            //     array( $this, 'gs_render_settings_field' ), // Callback
            //     'gs-settings', // Page
            //     'gs_setting_section', // Section 
            //     array (
            //         'parent'        => 'gs_options', // Option name
            //         'id'            => 'class_levels', // Field ID
            //         'class'         => 'regular-text one-line', // Field ID
            //         'type'          => 'textarea', // Field Type
            //         'subtype'       => 'text', // Field Subtype
            //         'name'          => 'class_levels', // Field Name
            //         'description'   => __('Mỗi trình độ viết cách nhau 1 dấu ";"', GS_TEXTDOMAIN),
            //         'options'       => array(
            //             'required'  => true,
            //             'rows'      => 5
            //         )
            //     ) // Callback Arguments          
            // );

            add_settings_field(
                'class_caphoc', // ID
                'Cấp học', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'class_caphoc', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'class_caphoc', // Field Name
                    'description'   => __('Mỗi cấp học viết cách nhau 1 dấu ";"', GS_TEXTDOMAIN),
                    'options'       => array(
                        'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'class_province', // ID
                'Tỉnh/thành', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'class_province', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'class_province', // Field Name
                    'description'   => __('Mỗi tỉnh viết cách nhau 1 dấu ";"', GS_TEXTDOMAIN),
                    'options'       => array(
                        'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'google_api_key', // ID
                'Google API Key', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'google_api_key', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'input', // Field Type
                    'subtype'       => 'password', // Field Subtype
                    'name'          => 'google_api_key', // Field Name
                    'description'   => '',
                    'options'       => array(
                        'required'  => true,
                        // 'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'fanpage_url', // ID
                'Fanpage URL', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'fanpage_url', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'fanpage_url', // Field Name
                    'description'   => __('Mỗi page viết cách nhau 1 dấu ";" -- VD: Hà Nội :: *page_url*; Hà Nam :: *page_url*;...', GS_TEXTDOMAIN),
                    'options'       => array(
                        'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_section(
                'gs_noty_setting_section', // ID
                'Gửi thông báo gia sư', // Title
                '', // Callback
                'gs-settings' // Page
            );

            add_settings_field(
                'wemap_key', // ID
                'WeMap API Key', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_noty_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'wemap_key', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'input', // Field Type
                    'subtype'       => 'password', // Field Subtype
                    'name'          => 'wemap_key', // Field Name
                    'description'   => '',
                    'options'       => array(
                        //'required'  => true,
                        // 'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'classroom_publish_noty_distance', // ID
                'Khoảng cách tối đa', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_noty_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'classroom_publish_noty_distance', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'input', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'classroom_publish_noty_distance', // Field Name
                    'description'   => 'Khoảng cách tối đã từ địa chỉ của gia sư đến lớp học để có thể nhận được thông báo. Đơn vị: mét(m)',
                    'options'       => array(
                        //'required'  => true,
                        // 'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'classroom_publish_noty_sms', // ID
                'Nội dung tin nhắn', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_noty_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'classroom_publish_noty_sms', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'classroom_publish_noty_sms', // Field Name
                    'description'   => __('Nội dung tin nhắn có thể dùng các thông tin động như: {class_ID}, {class_name}, {class_address}, {site_url}, {site_name}', GS_TEXTDOMAIN),
                    'options'       => array(
                        // 'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'classroom_publish_noty_mail_subject', // ID
                'Tiêu đề mail', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_noty_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'classroom_publish_noty_mail_subject', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'input', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'classroom_publish_noty_mail_subject', // Field Name
                    'description'   => '',
                    'options'       => array(
                        //'required'  => true,
                        // 'rows'      => 5
                    )
                ) // Callback Arguments          
            );

            add_settings_field(
                'classroom_publish_noty_mail', // ID
                'Nội dung mail', // Title 
                array( $this, 'gs_render_settings_field' ), // Callback
                'gs-settings', // Page
                'gs_noty_setting_section', // Section 
                array (
                    'parent'        => 'gs_options', // Option name
                    'id'            => 'classroom_publish_noty_mail', // Field ID
                    'class'         => 'regular-text one-line', // Field ID
                    'type'          => 'textarea_html', // Field Type
                    'subtype'       => 'text', // Field Subtype
                    'name'          => 'classroom_publish_noty_mail', // Field Name
                    'description'   => __('Nội dung mail có thể dùng các thông tin động như: {class_ID}, {class_name}, {class_address}, {site_url}, {site_name}', GS_TEXTDOMAIN),
                    'options'       => array(
                        // 'required'  => true,
                        'rows'      => 5
                    )
                ) // Callback Arguments          
            );
        }

        /** 
         * Get the settings option array and print one of its values
         */
        function gs_render_settings_field($args) {
            $field_options = inputOptions($args['options']);
            $field_name = isset($args['parent']) ? $args['parent'].'['.$args['name'].']' : $args['name'];
            
            switch($args['type']) {
                case 'input':
                    switch($args['subtype']){
                        case 'text':
                        case 'password':
                        case 'number' :
                            printf(
                                '<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" %5$s value="%6$s" />',
                                $args['subtype'],
                                $args['class'],
                                $args['id'],
                                $field_name,
                                $field_options,
                                $args['subtype'] == 'number' ? intval( isset( $this->options[$args['id']] ) ? esc_attr( $this->options[$args['id']]) : '' ) : sanitize_text_field( isset( $this->options[$args['id']] ) ? esc_attr( $this->options[$args['id']]) : '' )
                            );
                            printf('<p>%1$s</p>', $args['description']);
                            break;
                        case 'number':
                            printf(
                                '<input type="%1$s" class="%2$s" id="%3$s" name="%4$s" %5$s value="%6$s" />',
                                $args['subtype'],
                                $args['class'],
                                $args['id'],
                                $field_name,
                                $field_options,
                                intval( isset( $this->options[$args['id']] ) ? esc_attr( $this->options[$args['id']]) : '' )
                            );
                            printf('<p>%1$s</p>', $args['description']);
                            break;
                        default :
                            echo __('Chọn loại input cho trường cài đặt!!');
                    }
                    break;
                case 'textarea':
                    printf(
                        '<textarea type="%1$s" class="%2$s" id="%3$s" name="%4$s" %5$s>%6$s</textarea>',
                        $args['subtype'],
                        $args['class'],
                        $args['id'],
                        $field_name,
                        $field_options,
                        sanitize_text_field( isset( $this->options[$args['id']] ) ? esc_attr( $this->options[$args['id']]) : '' )
                    );
                    printf('<p>%1$s</p>', $args['description']);
                    break;
                case 'textarea_html':
                    wp_editor( html_entity_decode(isset( $this->options[$args['id']] ) ? sanitize_text_field(esc_attr( $this->options[$args['id']])) : ''), $args['id'], array(
                        'wpautop' => false, 
                        'textarea_name' => $field_name,
                        'media_buttons' => true,
                        'tinymce' => true,
                    ) );
                    printf('<p>%1$s</p>', $args['description']);
                    break;
                default: 
                    echo __('Chọn loại input cho trường cài đặt!!');
            }
            
        }
    }
    
    $settings = new GsSettingsPage();
}