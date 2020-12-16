<?php
/**
* Template Name: Register
*/

get_header( );

?>

<div class="container">
    <div class="row">
        <div class="col-8 offset-2">
            <div class="card">
                <?php echo custom_registration_function(); ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer( );