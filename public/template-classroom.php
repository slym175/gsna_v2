<?php
/**
* Template Name: Classroom
*/


get_header();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$filter_province = isset($_REQUEST['filter_province']) && $_REQUEST['filter_province'] != "Chọn tỉnh/thành phố" ? $_REQUEST['filter_province'] : "";
$filter_district = isset($_REQUEST['filter_district']) && $_REQUEST['filter_district'] != "Chọn quận/huyện" ? $_REQUEST['filter_district'] : "";
$filter_subject = isset($_REQUEST['filter_subject']) && $_REQUEST['filter_subject'] != "0" ? $_REQUEST['filter_subject'] : "";
$filter_caphoc = isset($_REQUEST['filter_caphoc']) && $_REQUEST['filter_caphoc'] != "0" ? $_REQUEST['filter_caphoc'] : "";
$filter_target = isset($_REQUEST['filter_target']) && $_REQUEST['filter_target'] != "0" ? $_REQUEST['filter_target'] : "";
$filter_formats = isset($_REQUEST['filter_formats']) && $_REQUEST['filter_formats'] != "0" ? $_REQUEST['filter_formats'] : "";

// echo "Tinh";
// print_r($filter_province );
// echo "<br>Huyen";
// print_r($filter_district );
// echo "<br>Mon";
// print_r($filter_subject );
// echo "<br>Cap hoc";
// print_r($filter_caphoc );
// echo "<br>Muc tieu";
// print_r($_REQUEST['filter_target'] );
// echo "<br>Hinh thuc";
// print_r($filter_formats );


$args = array(
    'post_type'         => 'classroom',
    'paged'             => $paged,
    'post_status'       => array('publish'),
    'orderby'           => 'publish_date',
    'order'             => 'DESC',
    'posts_per_page'    => get_option( 'gs_options' )['classes_per_page'] ? get_option( 'gs_options' )['classes_per_page'] : 12,
    'tax_query'         => array(
        $filter_subject != "" ? 
        array(
            'taxonomy'  => 'class_subject',
            'field'     => 'term_id',
            'terms'     => intval($filter_subject),
        ) : ''
    ),
    'meta_query'        => array(
        'relation' => 'AND',
        array(
            'key'       => 'class_tutor',
            'value'     => 0,
            'compare'   => "=",
        ),
        array(
            'relation'      => 'OR',
            $filter_target != "" ? 
            array(
                'key'       => 'class_target',
                'value'     => $filter_target,
                'compare'   => 'LIKE',
            ) : '',
            $filter_caphoc != "" ?
            array(
                'key'       => 'class_caphoc',
                'value'     => $filter_caphoc,
                'compare'   => 'LIKE',
            ) : '',
            $filter_province != "" ?
            array(
                'key'       => 'class_address',
                'value'     => $filter_province,
                'compare'   => 'LIKE',
            ) : '',
            $filter_district != "" ?
            array(
                'key'       => 'class_address',
                'value'     => $filter_district,
                'compare'   => 'LIKE',
            ) : '',
            $filter_formats != "" ?
            array(
                'key'       => 'class_format',
                'value'     => $filter_formats,
                'compare'   => 'LIKE',
            ) : ''
        )
    )
);

$classrooms = new WP_Query($args);

?>



<?php do_action( 'flatsome_before_blog' ); ?>

<div class="classroom-section">
    <div class="row breadcrumbs-section">
        <div class="col">
            <div class="breadcrumbs">
                <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <form action="" method="GET" class="form-filter">
                <input type="text" name="keywords" id="form-keywords">
                <button class="submit-form" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h5 class="text-primary"><i class="fa fa-filter" aria-hidden="true"></i> Bộ lọc</h5>
            <form action="" method="POST" class="form-filter">
                <!-- <select name="filter_subject" id="filter_subject">

                </select>
                <select name="filter_province" id="filter_province">

                </select>
                <select name="filter_district" id="filter_district">

                </select>
                <select name="filter_target" id="filter_target">

                </select>
                <select name="filter_caphoc" id="filter_caphoc">

                </select>
                <select name="filter_formats" id="filter_formats">

                </select> -->  
                <?php 
                // echo "<pre>";
                // print_r(get_class_locations()); 
                // echo "</pre>";
                ?>

                <?php $terms = getSubjectNoParent(); ?>
                <div class="filter-parent">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn môn học</span>
                    </div>
                    <div class="filter-menu">
                        <div class="filter-menu-list" data-name="môn học">
                            <?php foreach($terms as $term) : ?>
                                <div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="filter_subject[]" value="<?= $term->term_id ?>" id="subject<?= $term->term_id ?>">
                                        <label for="subject<?= $term->term_id ?>" class="checkmark"><?= $term->name ?></label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <?php $privinces = get_class_locations(); ?>
                <div class="filter-parent single">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn tỉnh/thành</span>
                    </div>
                    <div class="filter-menu">
                        <div class="filter-menu-list" data-name="tỉnh/thành">
                            <?php foreach($privinces as $key => $privince) : ?>
                                <div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="filter_province" value="<?= $privince['text'] ?>" id="province<?= $key ?>">
                                        <label for="province<?= $key ?>" class="checkmark"><?= $privince['text'] ?></label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <div class="filter-parent">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn quận/huyện</span>
                    </div>
                    <div class="filter-menu">
                        <div class="filter-menu-list" data-name="quận/huyện">
                            
                        </div>
                    </div>
                </div>
                <!-- <a class="submit-form" href="javascript:void(0)" onclick="submitFormFilter(this)"><span class="dashicons-before dashicons-search"></span></a> -->
                <button class="submit-form" type="submit"><i class="fa fa-filter"
                        aria-hidden="true"></i><?= __('Lọc', GS_TEXTDOMAIN) ?></button>
            </form>
            <?php tutorUncompletedProfileMessage(get_current_user_id()) ?>
        </div>
    </div>
    <div class="banner-grid-wrapper" style="margin-top: 10px">
        <div class="banner-grid row row-grid row-small">
            <div class="col small-12 large-12">
                <?php //if($filter_province != "" || $filter_district != "" || $filter_subject != "" || $filter_caphoc != "" || $filter_target != "") {
                        // printf('Có %1$d kết quả tìm kiếm%2$s %3$s %4$s %5$s %6$s',
                            //$classrooms->found_posts,
                            //": ",
                            //$filter_province != "" ? $filter_district.", ".$filter_province : "",
                            //$filter_subject != "" ? get_term_by('term_id', $filter_subject, 'class_subject')->name : "",
                            //$filter_caphoc != "" ? get_term_by('term_id', $filter_caphoc, 'class_theme')->name : "",
                            //$filter_target != "" ? $filter_target : ""
                        //);
                    //} else { ?>
                <p style="margin-bottom: 0">Có <?= $classrooms->found_posts ?> kết quả</p>
                <?php //} ?>
            </div>
            <?php while($classrooms->have_posts(  )) : $classrooms->the_post(  ); ?>
            <div class="col grid-col small-12 large-3 grid-col-1 widget woocommerce widget_products">
                <div class="card">
                    <div class="card-header">
                        <p class="class_id"><?= get_post_meta( get_the_ID(  ), 'class_ID', true ) ?></p>
                    </div>
                    <div class="card-body">
                        <a href="<?= the_permalink( ) ?>" class="class_title"><i class="fa fa-book"
                                aria-hidden="true"></i><?php echo the_title( ) ?></a>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_address', true ) ) { ?>
                        <p class="class_address"><i class="fa fa-map-marker"
                                aria-hidden="true"></i><?= get_post_meta( get_the_ID(  ), 'class_address', true ) ?></p>
                        <?php } ?>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_price', true ) ) { ?>
                        <p class="class_price"><i class="fa fa-usd"
                                aria-hidden="true"></i><?php get_classroom_price() ?></p>
                        <?php } ?>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_times', true ) ) { ?>
                        <p class="class_price"><i class="fa fa-clock-o"
                                aria-hidden="true"></i><?= get_post_meta( get_the_ID(  ), 'class_times', true ) ?></p>
                        <?php } ?>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_target', true ) ) { ?>
                        <p class="class_target"><i class="fa fa-bookmark-o"
                                aria-hidden="true"></i><?= __('Yêu cầu: ', GS_TEXTDOMAIN) ?><?= get_post_meta( get_the_ID(  ), 'class_target', true ) ?>
                        </p>
                        <?php } ?>

                    </div>
                    <div class="card-footer">
                        <a href="<?= the_permalink( ) ?>"><?= __('Xem chi tiết', GS_TEXTDOMAIN) ?> <i
                                class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
            <?php endwhile ?>
            <div class="pagination">
                <?php 
                    echo paginate_links( array(
                        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                        'total'        => $classrooms->max_num_pages,
                        'current'      => max( 1, $paged ),
                        'format'       => '?paged=%#%',
                        'show_all'     => false,
                        'type'         => 'plain',
                        'end_size'     => 2,
                        'mid_size'     => 1,
                        'prev_next'    => true,
                        'prev_text'    => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
                        'next_text'    => '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
                        'add_args'     => false,
                        'add_fragment' => '',
                    ) );
                ?>
            </div>
        </div>
    </div>
</div>
<?php
wp_reset_postdata();

do_action( 'flatsome_after_blog' );

get_footer();