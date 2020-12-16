<?php 
get_header();
?>

<div class="row">
    <?php
        while(have_posts(  )) {
            the_post( );
            ?>
                <h4>Lớp: <?= the_title( ) ?></h4>
                <p><i class="fa fa-clock"></i> <?= the_date( 'd/m/Y' ) ?></p>
                <div><?= the_excerpt(  ) ?></div>
                <div><?= the_content( ) ?></div>
                <div>Môn: <?php echo get_the_terms(get_the_ID(  ), 'class_subject')[0]->name ?></div>
                <div>Địa điểm: <?php echo get_post_meta( get_the_ID(  ), 'class_address', true ) ?></div>
                <div>Thù lao: <?php get_classroom_price() ?></div>
            <?php
        }
    ?>
</div>


<?php 
get_footer( );
