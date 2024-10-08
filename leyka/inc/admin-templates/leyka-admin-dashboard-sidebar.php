<?php if( !defined('WPINC') ) die;
/** Admin Dashboard sidebar template */

/** @var $this Leyka_Admin_Setup */

require_once(LEYKA_PLUGIN_DIR.'inc/settings/leyka-class-settings-factory.php');?>

<div class="leyka-info-sidebar-part">

    <div class="leyka-logo"><img src="<?php echo esc_url(LEYKA_PLUGIN_BASE_URL); ?>img/dashboard/logo-leyka.svg" alt=""></div>

    <div class="leyka-description">
        <?php esc_html_e('Leyka is a simple donations collection & management system for your website', 'leyka');?>
    </div>

    <div class="leyka-bottom-link leyka-official-website">
        <a href="//leyka.org/docs/what-is-leyka/" target="_blank"><?php esc_html_e('Go to the plugin documentation', 'leyka');?></a>
    </div>

    <div class="leyka-bottom-link leyka-wizard-link">
        <a href="<?php echo esc_url( admin_url('/admin.php?page=leyka_settings_new&screen=wizard-init') );?>" class="init-wizard-link"><?php esc_html_e('To the step-by-step setup', 'leyka');?></a>
    </div>

</div>

<?php $init_wizard_controller = Leyka_Settings_Factory::get_instance()->get_controller('init');
$main_settings_steps = $init_wizard_controller->navigation_data[0]['stage_id'] === 'rd' ?
    $init_wizard_controller->navigation_data[0]['sections'] : [];

if($main_settings_steps) {?>
    <div class="leyka-info-sidebar-part">

        <h3><?php esc_html_e('My data', 'leyka');?></h3>

        <div class="sidebar-part-content settings-state">
            <?php foreach($main_settings_steps as $step) {

                $step_invalid_options = leyka_is_settings_step_valid($step['section_id']);?>

                <div class="settings-step-set">
                    <div class="step-setup-status <?php echo !is_array($step_invalid_options) ? 'step-valid' : 'step-invalid';?>"></div>
                    <div class="step-title-wrapper">
                        <div class="step-title"><?php echo esc_html( $step['title'] );?></div>

                        <?php if(is_array($step_invalid_options)) {?>

                            <div class="step-invalid-options">

                                <?php if(count($step_invalid_options) <= 5) {
                                    foreach($step_invalid_options as $option_id) { ?>
                                        <div class="invalid-option">
                                            <?php echo wp_kses_post( leyka_options()->get_title_of($option_id) ); ?>
                                        </div>
                                    <?php }
                                } else {?>
                                    <div class="invalid-option"><?php esc_html_e('Some option fields are not filled correctly', 'leyka');?></div>
                                <?php }?>

                            </div>
                        <?php }?>

                    </div>
                </div>

            <?php }?>
        </div>

    </div>
<?php }?>

<div class="leyka-info-sidebar-part">

    <h3><?php esc_html_e('Payment gateways', 'leyka');?></h3>

    <div class="sidebar-part-content gateways">

        <?php foreach(leyka()->get_gateways(['activation_status' => 'activating']) as $gateway) {?>
            <div class="gateway status-activating">
                <div class="module-logo"><img src="<?php echo esc_url( $gateway->icon_url );?>" alt=""></div>
                <div class="gateway-data">
                    <div class="gateway-title"><?php echo esc_html( $gateway->title );?></div>
                    <div class="gateway-activation-status"><a href="<?php echo esc_url( admin_url("/admin.php?page=leyka_settings&stage=payment&gateway=" . $gateway->id) ); ?>"><?php esc_html_e('Activating', 'leyka');?></a></div>
                </div>
            </div>
        <?php }?>

        <?php foreach(leyka()->get_gateways(['activation_status' => 'active']) as $gateway) {?>
            <div class="gateway status-active">
                <div class="module-logo"><img src="<?php echo esc_url( $gateway->icon_url );?>" alt=""></div>
                <div class="gateway-data">
                    <div class="gateway-title"><?php echo esc_html( $gateway->title );?></div>
                    <div class="gateway-activation-status"><?php esc_html_e('Active', 'leyka');?></div>
                </div>
            </div>
        <?php }?>

    </div>

    <div class="add-gateway-link">
        <a href="<?php echo esc_url( admin_url('admin.php?page=leyka_settings') );?>"><?php esc_html_e('Add gateway', 'leyka');?></a>
    </div>

</div>

<div class="leyka-info-sidebar-part">

    <h3><?php esc_html_e('Diagnostic data', 'leyka');?></h3>

    <div class="sidebar-part-content diagnostic-data">
        <div class="data-line"><?php esc_html_e('Leyka', 'leyka'); ?> <?php echo esc_html( LEYKA_VERSION );?></div>
        <div class="data-line">
            <?php $template = leyka()->get_template(leyka()->opt('donation_form_template'));
            // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
            echo esc_html__('Default template:', 'leyka').' '.esc_html__($template['name'], 'leyka');?>
        </div>
        <div class="data-line php-actuality-status">

            <?php if(version_compare(phpversion(), '5.6') == -1) {
                $php_version_actuality = 'bad';
            } else if(version_compare(phpversion(), '5.6') >= 0 && version_compare(phpversion(), '7.1') == -1) {
                $php_version_actuality = 'average';
            } else if(version_compare(phpversion(), '7.1') >= 0 && version_compare(phpversion(), '7.2') == -1) {
                $php_version_actuality = 'good';
            } else {
                $php_version_actuality = 'excellent';
            }?>

            <div class="php-version <?php echo esc_attr( $php_version_actuality ); ?>"><?php echo esc_html( 'PHP ' . phpversion() );?></div>

        </div>
        <div class="data-line"><?php echo 'WordPress '.esc_html(get_bloginfo('version'));?></div>

        <div class="data-line">

            <?php $protocol = wp_parse_url(home_url(), PHP_URL_SCHEME);
            echo esc_html__('Protocol:', 'leyka').' ';?>
            <span class="protocol <?php echo esc_attr( $protocol == 'https' ? 'safe' : 'not-safe' );?>"><?php echo esc_html( mb_strtoupper($protocol) );?></span>
        </div>

        <?php if(leyka_options()->opt('plugin_debug_mode')) {?>

        <div class="data-line">

            <?php $php_extensions_needed = ['curl', 'date', 'ereg', 'filter', 'ftp', 'gd', 'hash', 'iconv', 'json', 'libxml', 'mbstring', 'mysql', 'mysqli', 'openssl', 'pcre', 'simplexml', 'sockets', 'spl', 'tokenizer', 'xmlreader', 'xmlwriter', 'zlib',]; // According to https://wordpress.stackexchange.com/questions/42098/what-are-php-extensions-and-libraries-wp-needs-and-or-uses

            $php_extensions = get_loaded_extensions();

            foreach($php_extensions_needed as &$extension_needed) {
                $extension_needed = '<span class="php-ext '.(in_array($extension_needed, $php_extensions) ? '' : 'php-ext-missing').'">'.mb_strtolower($extension_needed).'</span>';
            }?>

            <span class="php-modules-title"><?php esc_html_e('PHP modules', 'leyka');?></span>
            <?php echo esc_html( implode(', ', $php_extensions_needed) );?>

        </div>

        <?php }?>

    </div>

</div>