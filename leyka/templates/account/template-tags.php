<?php if( !defined('WPINC') ) die;
/**
 * Donor's account template tags & helpers
 **/

if( !function_exists('leyka_get_donation_status_description_for_donor') ) {

}

if( !function_exists('leyka_get_donor_account_donations_list_item_html') ) {
    function leyka_get_donor_account_donations_list_item_html($is_hidden = false, $donation = false) {

        $is_hidden = !!$is_hidden;
        $placeholders = [
            'donation_status' => '#STATUS#',
            'donation_status_description' => '#STATUS_DESCR#',
            'donation_type' => '#TYPE#',
            'donation_type_description' => '#TYPE_DESCR#',
            'recurring_is_active' => '#RECURRING_IS_ACTIVE#',
            'init_recurring_donation' => '#INIT_RECURRING_DONATION#',
            'amount' => '#AMOUNT#',
            'currency_label' => '#CURR#',
            'gateway_label' => '#GATEWAY#',
            'pm_label' => '#PM#',
            'date' => '#DATE#',
            'campaign_title' => '#CAMPAIGN_TITLE#',
        ];

        if($donation) {

            $donation = Leyka_Donations::get_instance()->get_donation($donation);

            $placeholders = [
                'donation_status' => $donation->status,
                'donation_status_description' => $donation->status_description_for_donors,
                'donation_type' => $donation->type,
                'donation_type_description' => $donation->type_description,
                'recurring_is_active' => $donation->recurring_is_active ? 'recurring-is-active' : '',
                'init_recurring_donation' => $donation->is_init_recurring_donation ? 'init-recurring-donation' : '',
                'amount' => $donation->amount,
                'amount_formatted' => $donation->amount_formatted,
                'currency_label' => $donation->currency_label,
                'gateway_label' => $donation->gateway_label,
                'pm_label' => $donation->pm_label,
                'date' => $donation->date,
                'campaign_title' => mb_ucfirst($donation->campaign_title),
                'campaign_id' => $donation->campaign_id,
            ];

        }

        ob_start();?>

        <div class="item <?php echo esc_attr( $placeholders['donation_status'] );?> <?php echo esc_attr( $placeholders['donation_type'] );?> <?php echo esc_attr( $placeholders['recurring_is_active'] );?> <?php echo esc_attr( $placeholders['init_recurring_donation'] );?>" <?php echo wp_kses_post( $is_hidden ? 'style="display:none;"' : '');?>>
            <h4 class="item-title">
                <span class="field-q"><span class="field-q-tooltip <?php echo 'status-'.esc_attr($placeholders['donation_status']);?> <?php echo 'type-'.esc_attr($placeholders['donation_type']);?>">
                    <?php echo wp_kses_post( $placeholders['donation_type_description'] );?>
                    <br><br>
                    <?php echo wp_kses_post( $placeholders['donation_status_description'] );?>
                </span></span>

                <?php echo ($donation->status === 'refunded' ? esc_html__('Refunding:', 'leyka').' ' : '')
                    .esc_html($placeholders['amount_formatted']).' '.esc_html($placeholders['currency_label']);?>

            </h4>
            <span class="date"><?php echo esc_html( $placeholders['date'] );?></span>
            <p>
                <a href="<?php echo esc_url(get_permalink($placeholders['campaign_id']));?>"><?php echo esc_html( $placeholders['campaign_title'] );?></a>
            </p>

            <div class="donation-gateway-pm">
                <img src="<?php echo esc_url(LEYKA_PLUGIN_BASE_URL);?>img/star-icon-info-small.svg" alt="">
                <span class="gateway"><?php echo esc_html( $placeholders['gateway_label'] );?></span> /
                <span class="pm"><?php echo esc_html( $placeholders['pm_label'] );?></span>
            </div>

        </div>

    <?php $out = ob_get_contents();
        ob_end_clean();

        return apply_filters('leyka_donor_account_donations_history_item_html', $out, $donation);

    }
}