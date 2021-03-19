<?php
require_once( dirname( __DIR__ ) . '/gp-woocommerce-plugin.php' );

global $wpdb;

define("GP_TABLE_NAME", $wpdb->prefix . 'gp_wc_plugin');

/**
 *
 */
class GP_WC_Helper
{

  /**
   *
   */
  public static function create_table() {
    global $wpdb;

    $sql = 'CREATE TABLE '.GP_TABLE_NAME.' (
           id integer(9) unsigned NOT NULL AUTO_INCREMENT,
           status varchar(50) NOT NULL,
           comments varchar(50) NOT NULL,
           description text(500) NOT NULL,
           order_id int(9) NOT NULL,
           pg_transaction_id varchar(50) NOT NULL,
           PRIMARY KEY  (id)
           );';
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

  }

  /**
   *
   */
  public static function insert_data($status, $comments, $description, $dev_reference, $transaction_id) {
    global $wpdb;
    $wpdb->insert(
      GP_TABLE_NAME,
      array(
        'status'            => $status,
        'comments'          => $comments,
        'description'       => $description,
        'order_id'          => $dev_reference,
        'pg_transaction_id' => $transaction_id
      ),
      array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s'
      )
    );
  }

  /**
   *
   */
  public static function select_order($order_id) {
    global $wpdb;
    $myrows = $wpdb->get_results("SELECT * FROM ".GP_TABLE_NAME." where order_id = '$order_id' ", OBJECT);

    foreach ($myrows as $campos) {
      $transactionCode = $campos->pg_transaction_id;
    }
    return $transactionCode;
  }

  /**
   *
   */
  public static function get_checkout_params($order) {
    $order_data = $order->get_data();

    $description = "";
    foreach ($order->get_items() as $product) {
      $description .= $product['name'] . ',';
    }
    if (strlen($description) > 240) {
            $description = substr($description,0,240);
    }

    if (is_null($order_data['customer_id']) or empty($order_data['customer_id'])) {
        $uid = $orderId;
    } else {
        $uid = $order_data['customer_id'];
    }

    $vat = number_format(($order->get_total_tax()), 2, '.', '');

    $parametersArgs = array(
      'purchase_order_id'    => $order->get_id(),
      'purchase_amount'      => $order_data['total'],
      'purchase_description' => $description,
      'customer_phone'       => $order_data['billing']['phone'],
      'customer_email'       => $order_data['billing']['email'],
      'user_id'              => $uid,
      'vat'                  => $vat
    );

    return $parametersArgs;
  }

  /**
   *
   */
  public static function generate_ltp($order, $environment) {
    $url_ltp = ($environment == 'yes') ? 'https://noccapi-stg.'.GP_DOMAIN.GP_LTP : 'https://noccapi.'.GP_DOMAIN.GP_LTP ;
    $auth_token = GP_WC_Helper::generate_auth_token('server');

    $checkout_data = GP_WC_Helper::get_checkout_params($order);
    $redirect_url = $order->get_view_order_url();

    $data = [
      'user' => [
        'id'=> $checkout_data['user_id'],
        'name'=> $order->get_billing_first_name(),
        'last_name'=> $order->get_billing_last_name(),
        'email'=> $checkout_data['customer_email'],
      ],
      'order' => [
        'dev_reference' => $checkout_data['purchase_order_id'],
        'description' => $checkout_data['purchase_description'],
        'amount' => $checkout_data['purchase_amount'],
        'installments_type' => -1,
        'currency' => $order->get_currency(),
        'vat' => $checkout_data['vat'],
      ],
      'configuration' => [
        'partial_payment' => false,
        'expiration_days' => 1,
        'success_url' => $redirect_url,
        'failure_url' => $redirect_url,
        'pending_url' => $redirect_url,
        'review_url' => $redirect_url,
      ]
    ];

    $payload = json_encode($data);

    $ch = curl_init($url_ltp);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Auth-Token:' . $auth_token));

    try {
      $response = curl_exec($ch);
      $get_response = json_decode($response, true);
      $payment_url = $get_response['data']['payment']['payment_url'];
    } catch (Exception $e) {
      $payment_url = NULL;
    }
    curl_close($ch);
    return $payment_url;
  }

  /**
   *
   */
   public static function generate_auth_token($type) {
     $plugin = new GP_WC_Plugin();
     if ($type == 'server') {
       $app_code = $plugin->app_code_server;
       $app_key = $plugin->app_key_server;
     } elseif ($type == 'client') {
       $app_code = $plugin->app_code_client;
       $app_key = $plugin->app_key_client;
     }

     $fecha_actual = time();
     $variableTimestamp = (string)($fecha_actual);
     $uniq_token_string = $app_key . $variableTimestamp;
     $uniq_token_hash = hash('sha256', $uniq_token_string);
     $auth_token = base64_encode($app_code . ';' . $variableTimestamp . ';' . $uniq_token_hash);

     return $auth_token;
   }
}
