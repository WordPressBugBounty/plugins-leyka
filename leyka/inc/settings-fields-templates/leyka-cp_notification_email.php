<?php if( !defined('WPINC') ) die;

/** Custom field group for the CP payments cards. */

/** @var $this Leyka_Custom_Setting_Block A block for which the template is used. */?>

<div class="<?php echo esc_attr( $this->field_type );?> custom-block-captioned-screens">

    <span class="info2copy leyka-wizard-copy2clipboard"><?php echo esc_html(get_option('admin_email'));?></span>

    <div class="captioned-screen">

        <p><?php esc_html_e('Turn on the "Email notifications" field flag and insert the email to the field, as screenshot shows:', 'leyka');?></p>

        <div class="screen-wrapper">
            <img src="<?php echo esc_attr( LEYKA_PLUGIN_BASE_URL ); ?>img/cp/cp_notification_email.png" class="leyka-instructions-screen" alt="">
            <img src="<?php echo esc_attr( LEYKA_PLUGIN_BASE_URL ); ?>img/icon-zoom-screen.svg" class="zoom-screen" alt="">
        </div>
        <img src="<?php echo esc_attr( LEYKA_PLUGIN_BASE_URL ); ?>img/cp/cp_notification_email.png" class="leyka-instructions-screen-full" alt="">

    </div>

</div>