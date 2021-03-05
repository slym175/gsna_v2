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
// $filter_target = isset($_REQUEST['filter_target']) && $_REQUEST['filter_target'] != "0" ? $_REQUEST['filter_target'] : "";
$filter_formats = isset($_REQUEST['filter_formats']) && $_REQUEST['filter_formats'] != "0" ? $_REQUEST['filter_formats'] : "";

$meta_query = [];
$meta_query['relation'] = 'OR';

if($filter_district != "" && is_array($filter_district)) {
    foreach ( $filter_district as $district ) {
        $meta_query[] = [
            'key'     => 'class_address',
            'value'   => preg_replace('/Quận |Huyện /', '', $district),
            'compare' => 'LIKE',
        ];
    }
}

$keywords = isset($_REQUEST['keywords']) ? $_REQUEST['keywords'] : "";

if($keywords != "") {
    $args = array(
        'post_type'         => 'classroom',
        'paged'             => $paged,
        'post_status'       => array('publish'),
        'orderby'           => 'publish_date',
        'order'             => 'DESC',
        'posts_per_page'    => get_option( 'gs_options' )['classes_per_page'] ? get_option( 'gs_options' )['classes_per_page'] : 12,
        's'                 => $keywords
    );
} else {
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
                'terms'     => $filter_subject,
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
                'relation'      => 'AND',
                $filter_caphoc != "" ?
                array(
                    'key'       => 'class_caphoc',
                    'value'     => $filter_caphoc,
                    'compare'   => 'IN',
                ) : '',
                $filter_formats != "" ?
                array(
                    'key'       => 'class_format',
                    'value'     => $filter_formats,
                    'compare'   => 'IN',
                ) : '',
                $filter_province != "" ? (
                    $filter_district == "" ?
                    array(
                        'key'       => 'class_address',
                        'value'     => $filter_province,
                        'compare'   => 'LIKE',
                    ) : ''
                ) : '',
                
            ),
            $filter_district != "" ?
            $meta_query : '',
        )
    );
}


$classrooms = new WP_Query($args);

?>

<?php 
    if(isset($_GET['fix'])) {
        echo "<pre>";
        print_r( $args );
        echo "</pre>";
    }
?>

<?php do_action( 'flatsome_before_blog' ); ?>

<div class="classroom-section">
    <div class="row breadcrumbs-section row-grid row-small">
        <div class="col small-12">
            <div class="breadcrumbs">
                <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="row row-grid row-small">
        <div class="col small-12">
            <form action="" method="GET" class="form-filter">
                <input type="text" name="keywords" id="form-keywords">
                <button class="submit-form" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
    </div>

    <div class="row filter-section row-grid row-small">
        <div class="col small-12">
            <h5 class="text-primary"><i class="fa fa-filter" aria-hidden="true"></i> Bộ lọc</h5>
            <form action="" method="GET" class="form-filter">
                <?php $terms = getSubjectNoParent(); ?>
                <div class="filter-parent" id="filter-subject">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn môn học</span>
                    </div>
                    <div class="filter-menu">
                        <div class="filter-menu-list" data-name="môn học">
                            <?php foreach($terms as $term) : ?>
                                <div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" <?= isset($_REQUEST['filter_subject']) && in_array($term->term_id, $_REQUEST['filter_subject']) ? 'checked' : '' ?> name="filter_subject[]" value="<?= $term->term_id ?>" id="subject<?= $term->term_id ?>">
                                        <label for="subject<?= $term->term_id ?>" class="checkmark"><?= $term->name ?></label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="filter-actions">
                            <span class="filter-actions-button filter-actions__select">Chọn tất cả</span>
                            <span class="filter-actions-button filter-actions__delete">Xóa tất cả</span>
                        </div>
                    </div> 
                </div>

                <?php $provinces = get_provinces_locations(); ?>
                
                <div class="filter-parent single" id="filter-province">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn tỉnh/thành</span>
                    </div>
                    <div class="filter-menu">
                        <div class="filter-menu-list" data-name="tỉnh/thành">
                            <?php foreach($provinces as $key => $province) : ?>
                                <div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" <?= isset($_REQUEST['filter_province']) && $province['text'] == $_REQUEST['filter_province'] ? 'checked' : '' ?> data-key="<?= $province['id'] ?>" name="filter_province" value="<?= $province['text'] ?>" id="province<?= $key ?>">
                                        <label for="province<?= $key ?>" class="checkmark"><?= $province['text'] ?></label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <div class="filter-parent" id="filter-district">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn quận/huyện</span>
                    </div>
                    <div class="filter-menu">
                        <?php foreach($provinces as $key => $province) : ?>
                            <div class="filter-menu-list d-none" data-pkey="<?= $provinces[$key]['id'] ?>" data-name="quận/huyện">
                                <?php foreach($provinces[$key]['district'] as $k => $district) : ?>
                                    <div class="district-display">
                                        <div class="form-check mb-2">
                                            <input type="checkbox" <?= isset($_REQUEST['filter_district']) && in_array($district['text'], $_REQUEST['filter_district']) ? 'checked' : '' ?> name="filter_district[]" value="<?= $district['text'] ?>" id="district<?= $provinces[$key]['id'] ?><?= $k ?>">
                                            <label for="district<?= $provinces[$key]['id'] ?><?= $k ?>" class="checkmark"><?= $district['text'] ?></label>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php endforeach ?>
                        <div class="filter-actions">
                            <span class="filter-actions-button filter-actions__select">Chọn tất cả</span>
                            <span class="filter-actions-button filter-actions__delete">Xóa tất cả</span>
                        </div>
                    </div>
                </div>

                <?php $caphocs = array_map(function($item) {
                        return rtrim(ltrim($item, " "), " ");
                    }, explode(';', get_option('gs_options')['class_caphoc'])); ?>
                <div class="filter-parent" id="filter-caphoc">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn cấp học</span>
                    </div>
                    <div class="filter-menu">
                        <div class="filter-menu-list" data-name="cấp học">
                            <?php foreach($caphocs as $key => $caphoc) : ?>
                                <div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" <?= isset($_REQUEST['filter_caphoc']) && in_array($caphoc, $_REQUEST['filter_caphoc']) ? 'checked' : '' ?> name="filter_caphoc[]" value="<?= $caphoc ?>" id="caphoc<?= $key ?>">
                                        <label for="caphoc<?= $key ?>" class="checkmark"><?= $caphoc ?></label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="filter-actions">
                            <span class="filter-actions-button filter-actions__select">Chọn tất cả</span>
                            <span class="filter-actions-button filter-actions__delete">Xóa tất cả</span>
                        </div>
                    </div>
                </div>

                <?php $formats = array_map(function($item) {
                        return rtrim(ltrim($item, " "), " ");
                    }, explode(';', get_option('gs_options')['tutor_formats'])); ?>
                <div class="filter-parent" id="filter-formats">
                    <div class="filter-title">
                        <span class="filter-notice">Chọn hình thức học</span>
                    </div>
                    <div class="filter-menu">
                        <div class="filter-menu-list" data-name="hình thức học">
                            <?php foreach($formats as $key => $format) : ?>
                                <div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" <?= isset($_REQUEST['filter_formats']) && in_array($format, $_REQUEST['filter_formats']) ? 'checked' : '' ?> name="filter_formats[]" value="<?= $format ?>" id="format<?= $key ?>">
                                        <label for="format<?= $key ?>" class="checkmark"><?= $format ?></label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="filter-actions">
                            <span class="filter-actions-button filter-actions__select">Chọn tất cả</span>
                            <span class="filter-actions-button filter-actions__delete">Xóa tất cả</span>
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
                        <?php if( get_post_meta( get_the_ID(  ), 'class_gender', true ) ) { $array_gt = array("Nam", "Nữ", "Không yêu cầu"); ?>
                        <p class="class_target"><i class="fa fa-transgender" aria-hidden="true"></i><?= __('Giới tính: ', GS_TEXTDOMAIN) ?><?= $array_gt[get_post_meta( get_the_ID(  ), 'class_gender', true )] ?>
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