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
);

$classrooms = new WP_Query($args);

?>
<div class="classroom-section">

    <div class="row">

        <form action="" method="POST" class="form-filter">
            <select name="filter_subject" id="filter_subject">

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

            </select>
            <!-- <a class="submit-form" href="javascript:void(0)" onclick="submitFormFilter(this)"><span class="dashicons-before dashicons-search"></span></a> -->
            <button class="submit-form" type="submit"><span class="fa fa-search"></span></button>
        </form>

        <?php tutorUncompletedProfileMessage(get_current_user_id()) ?>

        <div class="classroom-container" id="classroom-container">
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
                <p>Có <?= $classrooms->found_posts ?> kết quả</p>
            <?php //} ?>
            <div class="table-scroll">
                <table class="clasroom-table">
                    <thead>
                        <tr>
                            <th><?= __('Người đăng', GS_TEXTDOMAIN) ?></th>
                            <th><?= __('Nội dung lớp học tìm gia sư', GS_TEXTDOMAIN) ?></th>
                            <th><?= __('Học phí đề nghị', GS_TEXTDOMAIN) ?></th>
                            <th><?= __('Phí nhận lớp', GS_TEXTDOMAIN) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($classrooms->have_posts(  )) { $classrooms->the_post(  ); ?>
                        <tr class="clasroom-item">
                            <td>
                                <p><?= get_the_author() ?></p>
                                <p><?= get_the_date( 'd/m/Y' ) ?></p>
                            </td>
                            <td>
                                <h4 class="clasroom-title"><a href="<?= the_permalink() ?>"><?= the_title( ) ?></a></h4>
                                <div class="classroom-exceprt">
                                    <?= the_excerpt(  ) ?>
                                </div>
                                <ul class="clasroom-tags">
                                    <?php
                                        $class_subject = get_the_terms(get_the_ID(  ), 'class_subject');
                                        $class_theme = get_the_terms(get_the_ID(  ), 'class_theme'); 
                                        if($class_subject && isset($class_subject)) {
                                            foreach($class_subject as $key => $sub) {
                                            ?>
                                    <li><a href="<?= get_term_link($sub->term_id,'class_subject') ?>"><?= $sub->name ?></a>
                                    </li>
                                    <?php
                                            }
                                        }
                                        if($class_theme && isset($class_theme)) {
                                            foreach($class_theme as $key => $them) {
                                            ?>
                                    <li><a href="<?= get_term_link($them->term_id,'class_theme') ?>"><?= $them->name ?></a>
                                    </li>
                                    <?php
                                            }
                                        }
                                    ?>
                                </ul>
                            </td>
                            <td>
                                <p class="classroom_price"><?php get_classroom_price() ?></p>
                                <p>
                                    <?php 
                                if(get_registration_classroom_count(get_the_ID(  )) != 0) {
                                    echo "Có ".get_registration_classroom_count(get_the_ID(  ))." đề nghị";
                                } else {
                                    echo __("Chưa có đề nghị", GS_TEXTDOMAIN);
                                } ?>
                                </p>
                            </td>
                            <td>
                                <p class="classroom_fee"><?php get_classroom_fee() ?></p>
                                <a href="<?= the_permalink( ) ?>">Đăng ký</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
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
                    'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Classes', GS_TEXTDOMAIN ) ),
                    'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Classes', GS_TEXTDOMAIN ) ),
                    'add_args'     => false,
                    'add_fragment' => '',
                ) );
            ?>
        </div>

    </div>
</div>
<?php
wp_reset_postdata();
get_footer();