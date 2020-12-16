<?php 
    require_once GS_PLUGIN_DIR . "/admin/class_classroom_registration_table.php";
    
    //Create an instance of our package class...
    $listTable = new Custom_Registration_Class_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $listTable->prepare_items();
?>

<div class="wrap">
        
    <h2>Danh sách đăng ký lớp</h2>
    
    <?php
        $message = '';
        if ('delete' === $listTable->current_action()) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', GS_TEXTDOMAIN), count($_REQUEST['id'])) . '</p></div>';
        }
    ?>
        
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="classregistrations" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php 
            $listTable->search_box('search', 'id');
            $listTable->display()
        ?>
    </form>
        
</div>