<?php 
    // require_once GS_PLUGIN_DIR . "/admin/class_classroom_registration_table.php";
    
    // //Create an instance of our package class...
    // $listTable = new Custom_Registration_Class_List_Table();
    // //Fetch, prepare, sort, and filter our data...
    // $listTable->prepare_items();
?>

<div class="wrap">
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <!-- <form id="classregistrations" method="get"> -->
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <!-- <input type="hidden" name="page" value="<?php //echo $_REQUEST['page'] ?>" /> -->
        <!-- Now we can render the completed list table -->
        <?php //$listTable->display() ?>
    <!-- </form> -->
    <?php settings_errors(); ?>  
	<form method="POST" action="options.php">  
        <?php 
		    settings_fields( 'gs_general_settings' );
		    do_settings_sections( 'gs_general_settings' ); 
		?>              
		<?php submit_button(); ?>  
	</form> 
        
</div>