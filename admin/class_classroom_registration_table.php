<?php


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    // require_once(ABSPATH . 'wp-admin/includes/screen.php');
    // require_once(ABSPATH . 'wp-admin/includes/class-wp-screen.php');
    // require_once(ABSPATH . 'wp-admin/includes/template.php');
}

/**
 * Custom_Table_Example_List_Table class that will display our custom table
 * records in nice table
 */
class Custom_Registration_Class_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'classregistration',
            'plural' => 'classregistrations',
            'ajax' => true
        ));
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_user_id($item)
    {
        $actions = array(
            'edit'      => sprintf('<a href="?post_type=classroom&page=%1$s&action=%2$s&id=%3$s">%4$s</a>',$_REQUEST['page'],'edit',$item['id'], __('Edit', GS_TEXTDOMAIN)),
            'delete'    => sprintf('<a href="javascript:void(0)" onclick="removeRow(this)" data-row="%1$s">%2$s</a>', $item['id'], __('Delete', GS_TEXTDOMAIN)),
        );
        $tutor = get_user_by( 'ID', $item['user_id'] );
        $is_seen = $item['is_seen'] == 0 ? "un-seen" : "";
        return sprintf(
            '<strong><a class="row-title %1$s" href="%2$s">%3$s</a></strong>%4$s',
            $is_seen,
            sprintf('?post_type=classroom&page=%s&action=%s&id=%s',$_REQUEST['page'],'edit',$item['id']),
            $tutor->user_nicename && isset($tutor->user_nicename) ? $tutor->user_nicename : $tutor->display_name,
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_classroom_id($item)
    {
        $class_id = get_post( $item['classroom_id'] );
        $is_seen = $item['is_seen'] == 0 ? "un-seen" : "";
        return sprintf(
            '<strong><a class="row-title %1$s" href="%2$s">%3$s</a></strong>',
            $is_seen,
            get_edit_post_link($class_id->ID),
            $class_id->post_title
        );
    }

    function column_classroom_code($item)
    {
        $is_seen = $item['is_seen'] == 0 ? "un-seen" : "";
        return sprintf(
            '<p class="%1$s">%2$s</p>',
            $is_seen,
            get_field('class_ID', $item['classroom_id']) 
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_status($item)
    {
        $array_status = array('pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận');
        $is_seen = $item['is_seen'] == 0 ? "un-seen" : "";
        return sprintf(
            '<p class="%1$s">%2$s</p>',
            $is_seen,
            $array_status[trim($item['status'])]
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_created_at($item)
    {
        $is_seen = $item['is_seen'] == 0 ? "un-seen" : "";
        return sprintf(
            '<p class="%1$s">%2$s</p>',
            $is_seen,
            $item['created_at']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(
            'cb'                => '<input type="checkbox" />', //Render a checkbox instead of text
            'user_id'           => __('Gia sư', GS_TEXTDOMAIN),
            'classroom_code'    => __('Mã lớp', GS_TEXTDOMAIN),
            'classroom_id'      => __('Lớp học', GS_TEXTDOMAIN),
            'status'            => __('Trạng thái', GS_TEXTDOMAIN),
            'created_at'        => __('Ngày lập', GS_TEXTDOMAIN),
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'user_id'       => array('user_id', true),
            'classroom_id'  => array('classroom_id', true),
            'status'        => array('status', true),
            'created_at'    => array('created_at', true)
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    // function get_bulk_actions()
    // {
    //     $actions = array(
    //         'delete' => __('Delete', GS_TEXTDOMAIN),
    //     );
    //     return $actions;
    // }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    // function process_bulk_action()
    // {
    //     global $wpdb;
    //     $table_name = $wpdb->prefix . 'gs_user_classrooms'; // do not forget about tables prefix

    //     if ('delete' === $this->current_action()) {
    //         $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
    //         if (is_array($ids)) $ids = implode(',', $ids);

    //         if (!empty($ids)) {
    //             $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
    //         }
    //     }
    // }

    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'gs_user_classrooms'; // do not forget about tables prefix

        $per_page = $this->get_items_per_page('submenudata_per_page', 10); // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        $current_page = $this->get_pagenum();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created_at';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        $this->items = $data;

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}
