<?php 
get_header();
?>

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

<div class="banner-grid-wrapper" style="margin-top: 30px">
    <div class="banner-grid row row-grid row-small">
        <div class="col small-12">
            <?php tutorUncompletedProfileMessage(get_current_user_id()) ?>
        </div>
        <div class="col grid-col small-12 large-6 grid-col-1">
            <div class="col-inner single-classroom-details">
                <?php while(have_posts(  )) { the_post( ); ?>
                    <h1>Chi tiết lớp <?= get_post_meta( get_the_ID(  ), 'class_ID', true ) ?></h1>
                    <h4>Lớp: <?= the_title( ) ?></h4>
                    <p class="mb-1" style="margin-bottom: .5em"><i class="fa fa-clock"></i> <?= the_date( 'd/m/Y' ) ?> - <span><?= get_registration_classroom_count(get_the_ID(  )) != 0 ? "Có ".get_registration_classroom_count(get_the_ID(  ))." lượt đăng ký" : __('Chưa có đăng ký') ?></span></p>
                    <?php if(get_post_status(get_the_ID(  )) == "publish") : ?>
                        <p class="mb-1"><i class="fa fa-check-circle-o color-available" aria-hidden="true"></i> Tình trạng: Sẵn sàng</p>
                    <?php endif ?>
                    <hr>
                    <div class="mb-1">
                        <?php if( get_post_meta( get_the_ID(  ), 'class_address', true ) ) { ?>
                            <p class="class_address"><i class="fa fa-map-marker" aria-hidden="true"></i><?= get_post_meta( get_the_ID(  ), 'class_address', true ) ?></p>
                        <?php } ?>
                    </div>
                    <div class="mb-1">
                        <?php if( get_post_meta( get_the_ID(  ), 'class_price', true ) ) { ?>
                            <p class="class_price"><i class="fa fa-usd" aria-hidden="true"></i><?php get_classroom_price() ?> </p>
                        <?php } ?>
                    </div>
                    <div class="mb-1">
                        <?php if( get_post_meta( get_the_ID(  ), 'class_times', true ) ) { ?>
                            <p class="class_price"><i class="fa fa-clock-o" aria-hidden="true"></i><?= get_post_meta( get_the_ID(  ), 'class_times', true ) ?></p>
                        <?php } ?>
                    </div>
                    <div class="mb-1">
                        <?php if( get_post_meta( get_the_ID(  ), 'class_target', true ) ) { ?>
                            <p class="class_target"><i class="fa fa-bookmark-o" aria-hidden="true"></i><?= __('Yêu cầu: ', GS_TEXTDOMAIN) ?><?= get_post_meta( get_the_ID(  ), 'class_target', true ) ?></p>
                        <?php } ?>
                    </div>
                    <div class="mb-1">
                        <?php if( get_post_meta( get_the_ID(  ), 'class_gender', true ) ) { $array_gt = array("Nam", "Nữ", "Không yêu cầu"); ?>
                            <p class="class_target"><i class="fa fa-transgender" aria-hidden="true"></i><?= __('Giới tính: ', GS_TEXTDOMAIN) ?><?= $array_gt[get_post_meta( get_the_ID(  ), 'class_gender', true )] ?></p>
                        <?php } ?>
                    </div>
                    
                    <?php 
                        if(!empty( get_the_excerpt( ))) {
                            printf('<hr><h6>%1$s</h6>',
                                __('Đặc điểm học sinh')
                            );
                            the_excerpt(  );
                        }
                    ?>
                    <?php 
                        if(!empty( get_the_content() )) {
                            printf('<hr><h6>%1$s</h6>',
                                __('Thông tin bổ sung')
                            );
                            the_content(  );
                        }
                    ?>
                    <?php if( get_post_meta( get_the_ID(  ), 'class_fee', true ) ) { ?>
                        <p class="class_fee"><?php echo __("Phí nhận lớp: "); ?> <span><?php get_classroom_fee( get_the_ID(  ) ) ?></span> </p>
                    <?php } ?>
                    <a href="javascript:void()" class="btn-gs-classroom" data-classroom="<?= get_the_ID(  ) ?>" ><?= __('Đăng ký nhận lớp', GS_TEXTDOMAIN) ?></a>
                <?php } ?>

            </div>
        </div>
        <div class="col grid-col small-12 large-6 grid-col-1">
            <div class="col-inner box-shadow-1">
                <?php getClassroomAddressIframe(get_the_ID(  )) ?>
            </div>
            <p class="text-muted mt-4 mb-0" style="margin-top: 10px"><?= __('Lưu ý: bản đồ thể hiện địa chỉ lớp gia sư một cách tương đối.', GS_TEXTDOMAIN) ?></p>
        </div>
    </div>
</div>

<div class="banner-grid-wrapper" style="margin-top: 30px">
    <div class="banner-grid row row-grid row-small">
    <?php
        //for use in the loop, list 5 post titles related to first tag on current post
        //Get array of terms
        $terms = get_the_terms( get_the_ID(  ) , 'class_subject');
        //Pluck out the IDs to get an array of IDS
        $term_ids = wp_list_pluck($terms,'term_id');

        //Query posts with tax_query. Choose in 'IN' if want to query posts with any of the terms
        //Chose 'AND' if you want to query for posts with all terms
        $second_query = new WP_Query( array(
            'post_type' => 'products',
            'tax_query' => array(
                array(
                    'taxonomy'  => 'class_subject',
                    'field'     => 'id',
                    'terms'     => $term_ids,
                    'operator'  => 'IN' //Or 'AND' or 'NOT IN'
                )
            ),
            'posts_per_page' => 3,
            'ignore_sticky_posts' => 1,
            'orderby' => 'rand',
            'post__not_in'=>array(get_the_ID())
        ) );

        if($second_query->have_posts(  )) {
            ?>
            <h6><?= __('Lớp liên quan', GS_TEXTDOMAIN) ?></h6>
            <?php
        }

        //Loop through posts and display...
        while($second_query->have_posts(  )) : $second_query->the_post(  ); ?>
            <div class="col pb-0 small-12 large-3 widget woocommerce widget_products">
                <div class="card">
                    <div class="card-header">
                        <p class="class_id"><?= get_post_meta( get_the_ID(  ), 'class_ID', true ) ?></p>
                    </div>
                    <div class="card-body">
                        <a href="<?= the_permalink( ) ?>" class="class_title"><i class="fa fa-book" aria-hidden="true"></i><?php echo the_title( ) ?></a>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_address', true ) ) { ?>
                            <p class="class_address"><i class="fa fa-map-marker" aria-hidden="true"></i><?= get_post_meta( get_the_ID(  ), 'class_address', true ) ?></p>
                        <?php } ?>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_price', true ) ) { ?>
                            <p class="class_price"><i class="fa fa-usd" aria-hidden="true"></i><?php get_classroom_price() ?></p>
                        <?php } ?>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_times', true ) ) { ?>
                            <p class="class_price"><i class="fa fa-clock-o" aria-hidden="true"></i><?= get_post_meta( get_the_ID(  ), 'class_times', true ) ?></p>
                        <?php } ?>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_target', true ) ) { ?>
                            <p class="class_target">
                                <i class="fa fa-bookmark-o" aria-hidden="true"></i>
                                <?= __('Yêu cầu: ', GS_TEXTDOMAIN) ?><?= get_post_meta( get_the_ID(  ), 'class_target', true ) ?>
                            </p>
                        <?php } ?>
                        <?php if( get_post_meta( get_the_ID(  ), 'class_gender', true ) ) { $array_gt = array("Nam", "Nữ", "Không yêu cầu"); ?>
                            <p class="class_gender">
                                <i class="fa fa-venus-mars" aria-hidden="true"></i>
                                <?= __('Giới tính: ', GS_TEXTDOMAIN) ?><?= $array_gt[get_post_meta( get_the_ID(  ), 'class_gender', true )] ?>
                            </p>    
                        <?php } ?>
                        
                    </div>
                    <div class="card-footer">
                        <a href="<?= the_permalink( ) ?>"><?= __('Xem chi tiết', GS_TEXTDOMAIN) ?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        <?php endwhile ?>
    </div>
</div>
<div class="gs-popup-classroom d-none">
<div class="contents">
            <i class="fa fa-times"></i>
            <div class="noty"  style="margin-bottom: 6px; font-size: 28px; font-weight: bold"></div>
            <?php if(get_option( 'gs_options' )['fanpage_url']) : ?>
                <p style="margin-bottom: 6px; margin-top: 30px">Liên hệ fanpage để được hướng dẫn</p>
                <div>
                    <?php if( fanpageURLArray() && is_array(fanpageURLArray()) ) : foreach(fanpageURLArray() as $key => $page) : ?>
                        <a style="word-break: break-word;" href="<?= $page ?>"><?= $key ?></a> 
                        <?= array_key_last(fanpageURLArray()) == $key ? "" : " - " ?>
                    <?php endforeach; endif ?>
                </div>
            <?php endif ?>
        </div>
</div>
<?php 
get_footer( );