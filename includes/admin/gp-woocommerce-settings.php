<?php

return array (
    'staging' => array(
        'title' => __( 'Staging Environment', 'gp_woocommerce' ),
        'type' => 'checkbox',
        'label' => __( 'Use staging environment in ', 'gp_woocommerce' ).GP_FLAVOR.'.',
        'default' => 'yes'
    ),
    'enable_ltp' => array(
        'title' => __( 'Enable LinkToPay', 'gp_woocommerce' ),
        'type' => 'checkbox',
        'label' => __( 'If selected, LinkToPay(Bank transfer, cash) can be used to pay.', 'gp_woocommerce' ),
        'default' => 'no'
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
    'installments_type' => array(
      'title' => __('Installments Type', 'gp_woocommerce'),
      'type' => 'select',
      'default' => -1,
      'options' => array(
        -1 => __('Disabled', 'gp_woocommerce'),
        0  => __('Enabled', 'gp_woocommerce'),
      ),
      'description' => __('Select the installments type that will be enabled on the payment screen (Only on card payment).', 'gp_woocommerce')
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
