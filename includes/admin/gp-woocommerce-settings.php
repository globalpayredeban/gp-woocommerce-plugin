<?php

return array (
    'staging' => array(
        'title' => __( 'Staging Enviroment', 'gp_woocommerce' ),
        'type' => 'checkbox',
        'label' => __( 'Use the GlobalPay Gateway staging environment.', 'gp_woocommerce' ),
        'default' => 'yes'
    ),
    'title' => array(
        'title' => __( 'Title', 'gp_woocommerce' ),
        'type' => 'text',
        'description' => __( 'This controls the title which the user sees during checkout.', 'gp_woocommerce' ),
        'default' => __( 'GlobalPay Gateway', 'gp_woocommerce' ),
        'desc_tip' => true,
    ),
    'description' => array(
        'title' => __( 'Customer Message', 'gp_woocommerce' ),
        'type' => 'textarea',
        'default' => __('GlobalPay is a complete solution for online payments. Safe, easy and fast.', 'gp_woocommerce')
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
    'app_code_client' => array(
    'title' => __('App Code Client', 'gp_woocommerce'),
    'type' => 'text',
    'description' => __('Unique identifier in GlobalPay.', 'gp_woocommerce')
    ),
    'app_key_client' => array(
        'title' => __('App Key Client', 'gp_woocommerce'),
        'type' => 'text',
        'description' => __('Key used to encrypt communication with GlobalPay.', 'gp_woocommerce')
    ),
    'app_code_server' => array(
        'title' => __('App Code Server', 'gp_woocommerce'),
        'type' => 'text',
        'description' => __('Unique identifier in GlobalPay Server.', 'gp_woocommerce')
    ),
    'app_key_server' => array(
        'title' => __('App Key Server', 'gp_woocommerce'),
        'type' => 'text',
        'description' => __('Key used for reverse communication with GlobalPay Server.', 'gp_woocommerce')
    )
  );
