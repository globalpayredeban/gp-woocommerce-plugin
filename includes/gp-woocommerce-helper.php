<?php
require_once( dirname( __DIR__ ) . '/gp-woocommerce-plugin.php' );

global $wpdb;

define("GP_TABLE_NAME", $wpdb->prefix . 'gp_wc_plugin');

/**
 * Helper class to manage actions from the payment gateway and actions on database.
 */
class GP_WC_Helper
{

    /**
     * Creates the required table on Wordpress database.
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
     * Inserts data to the plugin table.
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
     * Gets a payment gateway transaction code for a Woocommerce order id.
     * @param string $order_id
     * @return string
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
     * Method that build the required params to initialize the payment checkout.
     * @param WC_Order $order
     * @return array
     */
    public static function get_checkout_params($order) {
        $order_data = $order->get_data();

        $description = '';
        foreach ($order->get_items() as $product) {
            $description .= $product['name'] . ',';
        }
        if (strlen($description) > 100) {
            $description = substr($description,0,99);
        }

        if (is_null($order_data['customer_id']) or empty($order_data['customer_id'])) {
            $uid = $order->id;
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
     * Method to manage the LinkToPay request.
     * @param WC_Order $order
     * @param string $environment
     * @return string URL to LinkToPay
     */
    public static function generate_ltp($order, $environment) {
        $url_ltp = ($environment == 'yes') ? 'https://noccapi-stg.'.GP_DOMAIN.GP_LTP : 'https://noccapi.'.GP_DOMAIN.GP_LTP ;
        $auth_token = GP_WC_Helper::generate_auth_token('server');
        $checkout_data = GP_WC_Helper::get_checkout_params($order);
        $redirect_url = $order->get_view_order_url();
        $plugin = new GP_WC_Plugin();

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
                'expiration_days' => $plugin->ltp_expiration,
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
        } catch (Exception $e) {
            curl_close($ch);
            return NULL;
        }
        $get_response = json_decode($response, true);

        $data = $get_response['data'] ?: [];
        if (array_key_exists('payment', $data)) {
            curl_close($ch);
            return $data['payment']['payment_url'];
        } else {
            if (curl_errno($ch)) {
                $response = curl_error($ch);
            } else {
                $response = json_encode($get_response);
            }
            curl_close($ch);
            ?>
            <div id="ltp-failed">
                <p class="alert alert-warning">
                    <?php _e('An error occurred generating the payment link, gateway response', 'gp_woocommerce')?>: <?php echo $response;?>
                </p>
            </div>
            <?php
            return NULL;
        }
    }

    /**
     * Method to generate the payment gateway token for authentication.
     * @param string $type server
     * @return string|void
     */
    public static function generate_auth_token($type) {
        $plugin = new GP_WC_Plugin();
        if ($type == 'server') {
            $app_code = $plugin->app_code_server;
            $app_key = $plugin->app_key_server;
        } elseif ($type == 'client') {
            $app_code = $plugin->app_code_client;
            $app_key = $plugin->app_key_client;
        } else {
            return;
        }

        $timestamp = (string)(time());
        $token_string = $app_key . $timestamp;
        $token_hash = hash('sha256', $token_string);

        return base64_encode($app_code . ';' . $timestamp . ';' . $token_hash);
    }

    /**
     * Method to calculate the payment gateway stokens used for authentication.
     * @param string $user_id
     * @param string $transaction_id
     * @return array containing the stokens for client and server credentials.
     */
    public static function get_stokens($user_id, $transaction_id)
    {
        $webhookObj = new GP_WC_Plugin();
        $stoken_client = md5($transaction_id . "_" . $webhookObj->app_code_client . "_" . $user_id . "_" . $webhookObj->app_key_client);
        $stoken_server = md5($transaction_id . "_" . $webhookObj->app_code_server . "_" . $user_id . "_" . $webhookObj->app_key_server);
        return array($stoken_server, $stoken_client);

    }

    /**
     * Method to show the installments on the payment page.
     * @param string $enable_installments
     * @return void
     */
    public static function get_installments_type($enable_installments)
    {
        $installments_options = [
            0  => __('Enable Installments', 'gp_woocommerce'),
        ];
        ?>
        <div class="select" id="installments_div">
            <select name="installments_type" id="installments_type">
                <option selected disabled><?php _e('Installments Type', 'gp_woocommerce'); ?>:</option>
                <option value=-1><?php _e('Without Installments', 'gp_woocommerce'); ?></option>
                <?php
                if ($enable_installments == 'yes')
                {
                    foreach($installments_options as $value => $text)
                    {
                        ?>
                        <option value=<?php echo $value;?>><?php echo $text; ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <br><br>
        </div>
        <?php
    }
}
