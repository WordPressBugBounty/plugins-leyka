<?php if( !defined('WPINC') ) die;

class Leyka_Eu_Options_Allocator extends Leyka_Ru_Options_Allocator {

    protected static $_instance;

    public function get_beneficiary_options() {

        return [
            ['section' => [
                'name' => 'receiver_country',
                'title' => __('Country', 'leyka'),
                'is_default_collapsed' => false,
                'options' => ['receiver_country', 'phone_format'],
            ],],
            ['section' => [
                'name' => 'beneficiary_org_name',
                'title' => __("Organization's official name and contacts", 'leyka'),
                'description' => __('These data we will use for reporting documents to your donors. All data can be found in documents', 'leyka'),
                'is_default_collapsed' => false,
                'options' => [
                    'org_full_name', 'org_short_name', 'org_face_fio_ip', 'org_face_position', 'org_address',
                    'org_state_reg_number', 'org_kpp', 'org_inn',
                ]
            ],],
            ['section' => [
                'name' => 'org_bank_essentials',
                'title' => __("Organization's bank essentials", 'leyka'),
                'description' => __('Data needed for accounting documents, as well as to connect the payment with receipt', 'leyka'),
                'is_default_collapsed' => false,
                'options' => ['org_bank_name', 'org_bank_account', 'org_bank_corr_account', 'org_bank_bic',]
            ],],
            ['section' => [
                'name' => 'terms_of_service',
                'title' => __('Offer', 'leyka'),
                'description' => __('To comply with all the formalities, you need to provide an offer to conclude a donation agreement. We have prepared a template option. Please check.', 'leyka'),
                'is_default_collapsed' => false,
                'options' => ['terms_of_service_text', 'agree_to_terms_link_action',]
            ],],
            ['section' => [
                'name' => 'terms_of_pd',
                'title' => __('Agreement on personal data', 'leyka'),
                'description' => __('<ul><li>In the framework of fundraising you will collect the personal data of recipients of donations.</li>
<li>"Consent to data processing" - binding instrument on the law FZ-152.</li>
<li>We have prepared the text of the agreement template, but you can edit it to your needs.</li>
<li>All personal data is stored on your site and will not be sent.</li></ul>', 'leyka'),
                'is_default_collapsed' => false,
                'options' => ['pd_terms_text', 'agree_to_pd_terms_link_action',]
            ],],
        ];

    }

}