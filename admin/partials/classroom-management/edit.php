<?php 
    global $wpdb;
    $table_name = $wpdb->prefix."gs_user_classrooms";
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

    $result = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $id" );

    if($result[0]->is_seen == 0) {
        $wpdb->update($table_name, array('is_seen'=>1), array('id'=>$id));
    }

    $tutor = get_user_by('ID', $result[0]->user_id );
    $class = get_post( $result[0]->classroom_id );
?>

<div class="wrap">
    <h2>Thông tin đăng ký lớp</h2>
    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="col-card col-flex">
                    <div class="col-avatar">
                        <?php 
                            $class_image = get_the_post_thumbnail_url( $class->ID, array(150, 150) ) ? get_the_post_thumbnail_url( $class->ID, array(150, 150) ) : "http://0.gravatar.com/avatar/6491f889bc5b05cb482546e90c2672e6?s=96&d=mm&r=g";
                        ?>
                        <img src="<?= $class_image ?>" alt="<?= $class->post_title ?>">
                    </div>
                    <div class="col-info">
                        <h3 class="card-title"><?php echo __('Thông tin lớp học', GS_TEXTDOMAIN) ?></h3>
                        <table>
                            <tr>
                                <th class="row-title"><?php echo __('Lớp', GS_TEXTDOMAIN) ?></th>
                                <td><?= $class->post_title ?></td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div><!-- /col-left -->

        <div id="col-right">
            <div class="col-wrap">
                <div class="col-card col-flex">
                    <div class="col-avatar">
                        <img src="<?= get_avatar_url( $tutor->data->ID ) ? get_avatar_url( $tutor->data->ID ) : 'https://www.w3schools.com/images/tshirt.jpg' ?>" alt="Avtar">
                    </div>
                    <div class="col-info">
                        <h3 class="card-title"><?php echo __('Thông tin gia sư', GS_TEXTDOMAIN) ?></h3>
                        <table>
                            <tr>
                                <?php 
                                    $tutor_name = "";
                                    $first_name = get_user_meta( $tutor->data->ID, 'first_name', true ); 
                                    $last_name = get_user_meta( $tutor->data->ID, 'last_name', true );
                                    if(isset($first_name) && isset($last_name) && $first_name && $last_name) {
                                        $tutor_name = $first_name ." ". $last_name;
                                    } else {
                                        $tutor_name = $tutor->data->user_nicename;
                                    }
                                ?>

                                <th class="row-title"><?php echo __('Tên gia sư', GS_TEXTDOMAIN) ?></th>
                                <td><?php echo $tutor_name ?></td>
                            </tr>
                            <tr>
                                <th class="row-title"><?php echo __('Số điện thoại', GS_TEXTDOMAIN) ?></th>
                                <td><?php echo esc_attr( get_the_author_meta( 'user_phone', $tutor->data->ID ) ); ?></td>
                            </tr>
                            <tr>
                                <th class="row-title"><?php echo __('Email', GS_TEXTDOMAIN) ?></th>
                                <td><?php echo $tutor->data->user_email ?>   </td>
                            </tr>
                            <tr>
                                <th class="row-title"><?php echo __('Trạng thái', GS_TEXTDOMAIN) ?></th>
                                <td>
                                    <select name="gs_status" id="gs_status" value="<?= $id ?>">
                                        <?php foreach(GS_STATUS as $key => $status) : ?>
                                            <option value="<?= $key ?>" <?= $result[0]->status == $key ? "selected" : "" ?>><?= $status ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /col-right -->

    </div>
</div>