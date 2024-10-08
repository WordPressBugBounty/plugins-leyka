<?php if( !defined('WPINC') ) die;
/** Admin Extensions list page template */

/** @var $this Leyka_Admin_Setup */

$extensions = leyka()->get_extensions();
$extensions_categories = Leyka_Extension::get_filter_categories_list();?>

<div class="main-area-top">

    <div class="filter-area leyka-modules-filter show">

        <div class="filter-toggle">
            <img class="show-filter" src="<?php echo esc_url(LEYKA_PLUGIN_BASE_URL);?>img/icon-gateway-filter-off.svg" alt="">
            <img class="hide-filter" src="<?php echo esc_url(LEYKA_PLUGIN_BASE_URL);?>img/icon-gateway-filter-on.svg" alt="">
        </div>

        <div class="filter-categories">
            <?php foreach($extensions_categories as $category_id => $category_label) {?>
                <a class="filter-category-item" data-category="<?php echo esc_attr( $category_id );?>" href="#">
                    <?php echo esc_html(Leyka_Extension::get_filter_category_label($category_id));?>
                </a>
            <?php }?>
        </div>

        <a class="filter-action filter-category-show-filter" href="#">
            <?php echo esc_html_x('Filter', 'An imperative verb (like "filter [something]")', 'leyka');?>
        </a>
        <a class="filter-action filter-category-reset-filter" href="#">
            <?php esc_html_e('Clear the filter', 'leyka');?>
        </a>

    </div>

</div>

<div class="main-area all-modules-settings all-extensions-settings">

    <div class="modules-cards-list">

        <?php foreach($extensions as $extension_id => $extension) { /** @var $extension Leyka_Extension */

            $activation_status = $extension->get_activation_status();?>

            <div class="module-card extension-card <?php echo esc_attr(implode(' ', $extension->get_filter_categories()));?>">

                <div class="module-card-header">

                    <div class="module-card-icon"><?php leyka_show_extension_logo($extension, true);?></div>

                    <div>
                        <div class="module-card-title">
                            <a class="module-settings-link" href="<?php echo esc_url(admin_url('admin.php?page=leyka_settings&stage=extensions&extension='.$extension->id));?>">
                                <?php echo wp_kses_post( $extension->title );?>
                            </a>
                        </div>
                        <div class="module-card-status <?php echo esc_attr( $activation_status );?>">
                            <?php echo esc_html(Leyka_Extension::get_activation_status_label($activation_status));?>
                        </div>
                    </div>

                </div>

                <div class="module-card-params extension-description"><?php echo wp_kses_post( $extension->description );?></div>

                <div class="module-card-action">
                    <a class="button extension-settings <?php echo 'button-primary';?> <?php echo esc_attr( 'leyka-card-'.$activation_status ); ?>" href="<?php echo esc_url( $extension->get_settings_url() );?>">
                        <img src="<?php echo esc_attr( LEYKA_PLUGIN_BASE_URL ) .'img/icon-gear.svg';?>" alt="">
                        <?php esc_html_e('Extension settings', 'leyka');?>
                    </a>
                </div>

            </div>

        <?php }?>

    </div>

</div>