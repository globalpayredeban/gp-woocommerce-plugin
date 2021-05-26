<?php

return array (
    'staging' => array(
        'title' => __( 'Staging Environment', 'gp_woocommerce' ),
        'type' => 'checkbox',
        'label' => __( 'Use staging environment in ', 'gp_woocommerce' ).GP_FLAVOR.'.',
        'default' => 'yes'
    ),
    'enable_card' => array(
        'title' => __( 'Enable Card Payment', 'gp_woocommerce' ),
        'type' => 'checkbox',
        'label' => __( 'If selected, card payment can be used to pay.', 'gp_woocommerce' ),
        'default' => 'no'
    ),
    'enable_ltp' => array(
        'title' => __( 'Enable LinkToPay', 'gp_woocommerce' ),
        'type' => 'checkbox',
        'label' => __( 'If selected, LinkToPay(Bank transfer, cash) can be used to pay.', 'gp_woocommerce' ),
        'default' => 'no'
    ),
    'ltp_expiration' => array(
        'title' => __( 'Expiration Days for LinkToPay', 'gp_woocommerce' ),
        'type' => 'number',
        'description' => __( 'This value controls the number of days that the generated LinkToPay will be available to pay.', 'gp_woocommerce' ),
        'default' => 1,
        'desc_tip' => true,
    ),
    'title' => array(
        'title' => __( 'Title', 'gp_woocommerce' ),
        'type' => 'text',
        'description' => __( 'This controls the title which the user sees during checkout page.', 'gp_woocommerce' ),
        'default' => GP_FLAVOR.' Gateway',
        'desc_tip' => true,
    ),
    'description' => array(
        'title' => __( 'Customer Message', 'gp_woocommerce' ),
        'type' => 'textarea',
        'description' => __( 'This controls the message which the user sees during checkout page.', 'gp_woocommerce' ),
        'default' => GP_FLAVOR.__(' is a complete solution for online payments. Safe, easy and fast.', 'gp_woocommerce
        ')
    ),
    'card_button_text' => array(
        'title' => __( 'Card Button Text', 'gp_woocommerce' ),
        'type' => 'text',
        'description' => __( 'This controls the text that the user sees in the card payment button.', 'gp_woocommerce' ),
        'default' => __('Pay With Card', 'gp_woocommerce'),
        'desc_tip' => true,
    ),
    'ltp_button_text' => array(
        'title' => __( 'LinkToPay Button Text', 'gp_woocommerce' ),
        'type' => 'text',
        'description' => __( 'This controls the text that the user sees in the LinkToPay button.', 'gp_woocommerce' ),
        'default' =>  __( 'Pay with Cash/Bank Transfer', 'gp_woocommerce' ),
        'desc_tip' => true,
    ),
    'checkout_language' => array(
        'title' => __('Checkout Language', 'gp_woocommerce'),
        'type' => 'select',
        'default' => 'en',
        'options' => array(
            'en' => 'EN',
            'es' => 'ES',
            'pt' => 'PT',
        ),
        'description' => __('User\'s preferred language for checkout window. English will be used by default.', 'gp_woocommerce')
    ),
    'enable_installments' => array(
        'title' => __('Enable Installments', 'gp_woocommerce'),
        'type' => 'checkbox',
        'default' => 'no',
        'label' => __('If selected, the installments options will be showed on the payment screen (Only on card payment).', 'gp_woocommerce')
    ),
    'app_code_client' => array(
        'title' => __('App Code Client', 'gp_woocommerce'),
        'type' => 'text',
        'description' => __('Unique commerce identifier in ', 'gp_woocommerce').GP_FLAVOR.'.'
    ),
    'app_key_client' => array(
        'title' => __('App Key Client', 'gp_woocommerce'),
        'type' => 'text',
        'description' => __('Key used to encrypt communication with ', 'gp_woocommerce').GP_FLAVOR.'.'
    ),
    'app_code_server' => array(
        'title' => __('App Code Server', 'gp_woocommerce'),
        'type' => 'text',
        'description' => __('Unique commerce identifier to perform admin actions on ', 'gp_woocommerce').GP_FLAVOR.'.'
    ),
    'app_key_server' => array(
        'title' => __('App Key Server', 'gp_woocommerce'),
        'type' => 'text',
        'description' => __('Key used to encrypt admin communication with ', 'gp_woocommerce').GP_FLAVOR.'.'
    )
);
