<?php
require_once( dirname( __FILE__ ) . '/gp-woocommerce-helper.php' );
require_once( dirname( __DIR__ ) . '/gp-woocommerce-plugin.php' );

/**
 *
 */
class WC_Payment_Webhook_GP
{
    /**
     * Function to update the Woocommerce order with the coming payment gateway data.
     */
    static function update_order($order, $parameters)
    {
        $status             = $parameters["transaction"]['status'];
        $status_detail      = (int)$parameters["transaction"]['status_detail'];
        $transaction_id     = $parameters["transaction"]['id'];
        $authorization_code = $parameters["transaction"]['authorization_code'];
        $dev_reference      = $parameters["transaction"]['dev_reference'];
        $payment_message    = $parameters["transaction"]['message'];
        $payment_stoken     = $parameters["transaction"]['stoken'];
        $user_id            = $parameters["user"]["id"];
        $comments           = '';

        if (!$payment_stoken) {
            return ['message' => 'time limit error', 'code' => 400];
        } elseif (!in_array($payment_stoken, GP_WC_Helper::get_stokens($user_id, $transaction_id))) {
            return ['message' => 'token error', 'code' => 203];
        }

        $status_order = $order->get_status();

        if (!in_array($status_order, ['cancelled', 'refunded'])) {
            $description = __("Payment Response: ", "gp_woocommerce") .
                __(" | Status: ", "gp_woocommerce") . $status .
                __(" | Status_detail: ", "gp_woocommerce") . $status_detail .
                __(" | Dev_Reference: ", "gp_woocommerce") . $dev_reference .
                __(" | Authorization_Code: ", "gp_woocommerce") . $authorization_code .
                __(" | Transaction_Code: ", "gp_woocommerce") . $transaction_id;

            if ($status_detail == 3) {
                $comments = __("Successful Payment", "gp_woocommerce");
                $order->update_status('processing');
                $order->reduce_order_stock();
                $order->add_order_note(__('The payment has been made successfully. Transaction Code: ', 'gp_woocommerce') . $transaction_id . __(' and its Authorization Code is: ', 'gp_woocommerce') . $authorization_code);
            } elseif (in_array($status_detail, [0, 1, 31, 35, 36])) {
                $comments = __("Pending Payment", "gp_woocommerce");
                $order->update_status('on-hold');
                $order->add_order_note(__('The payment is pending.', 'gp_woocommerce') . $transaction_id . __(' the reason is: ', 'gp_woocommerce') . $payment_message);
            } elseif (in_array($status_detail, [7, 34, 21, 22, 23, 24, 25, 26, 27, 28, 29])) {
                $order->update_status('refunded');
                $order->add_order_note(__('Transaction refunded: ', 'gp_woocommerce') . $transaction_id . __(' status: ', 'gp_woocommerce') . $payment_message);
            } elseif ($status_detail == 8) {
                $description = "Chargeback";
                $comments = __("Payment Cancelled", "gp_woocommerce");
                $order->update_status('cancelled');
                $order->add_order_note(__('The payment was cancelled. Transaction Code: ', 'gp_woocommerce') . $transaction_id . __(' the reason is chargeback. ', 'gp_woocommerce'));
            } else {
                $comments = __("Failed Payment", "gp_woocommerce");
                $order->update_status('failed');
                $order->add_order_note(__('The payment has failed. Transaction Code: ', 'gp_woocommerce') . $transaction_id . __(' the reason is: ', 'gp_woocommerce') . $payment_message);
            }

            GP_WC_Helper::insert_data($status, $comments, $description, $dev_reference, $transaction_id);
            return ['message' => 'order updated', 'code' => 200];
        } else {
            return ['message' => 'order cant change', 'code' => 200];
        }
    }
}
