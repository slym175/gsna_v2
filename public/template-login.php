<?php
/**
* Template Name: Login
*/
get_header( );

?>

<div class="container">
    <div class="row">
        <div class="col-8 offset-2">
            <div class="card">
                <?php echo wp_login_form_custom(); ?>
                <?php echo do_shortcode('[edit_profile_view]') ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer( );