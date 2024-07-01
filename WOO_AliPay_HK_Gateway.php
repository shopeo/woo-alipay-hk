<?php

use Shopeo\WooAlipayHK\Alipay\SignatureTool;
use Shopeo\WooAlipayHK\AliPayApi;

class WOO_AliPay_HK_Gateway extends \WC_Payment_Gateway {
	private $sandbox = false;
	private $client_id = '';
	private $public_key = '';
	private $private_key = '';

	public function __construct() {
		$this->id                 = 'woo_alipay_hk';
		$this->method_title       = __( 'ALIPAY HK', 'woo-alipay-hk' );
		$this->method_description = __( 'ALIPAY HK', 'woo-alipay-hk' );

		$this->title       = $this->method_title;
		$this->description = $this->method_description;
		$this->supports    = array( 'products' );
		$this->init_form_fields();
		$this->init_settings();
		$this->enabled     = $this->get_option( 'enabled' );
		$this->sandbox     = $this->get_option( 'sandbox' ) == 'yes';
		$this->client_id   = $this->get_option( 'client_id' );
		$this->public_key  = $this->get_option( 'public_key' );
		$this->private_key = $this->get_option( 'private_key' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );

		add_action( 'woocommerce_api_' . $this->id, array( $this, 'webhook' ) );
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woo-alipay-hk' ),
				'label'       => __( 'Enable Alipay', 'woo-alipay-hk' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'sandbox'     => array(
				'title'       => __( 'Sandbox', 'woo-alipay-hk' ),
				'label'       => __( 'Enable Sandbox', 'woo-alipay-hk' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'client_id'   => array(
				'title'       => __( 'Client ID', 'woo-alipay-hk' ),
				'type'        => 'text',
				'description' => __( 'Client ID', 'woo-alipay-hk' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'public_key'  => array(
				'title'       => __( 'Alipay Public Key', 'woo-alipay-hk' ),
				'type'        => 'textarea',
				'description' => __( 'Alipay Public Key', 'woo-alipay-hk' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'private_key' => array(
				'title'       => __( 'Your Private Key', 'woo-alipay-hk' ),
				'type'        => 'textarea',
				'description' => __( 'Your Private Key', 'woo-alipay-hk' ),
				'default'     => '',
				'desc_tip'    => true,
			),
		);
	}

	public function process_payment( $order_id ) {
		global $woocommerce;
		$order       = new WC_Order( $order_id );
		$alipayApi   = new AliPayApi( $this->client_id, $this->public_key, $this->private_key, $this->sandbox );
		$notifyUrl   = home_url() . '/wc-api/' . $this->id . '?id=' . $order_id;
		$redirectUrl = $this->get_return_url( $order );
		$result      = $alipayApi->newOrder( $order_id, $order->get_currency(), $order->get_total(), $notifyUrl, $redirectUrl );
		if ( $result ) {
			error_log( print_r( $result, true ) );
			$order->set_transaction_id( $result->paymentId );
			$order->save();
			$woocommerce->cart->empty_cart();

			return array( 'result' => 'success', 'redirect' => $result->normalUrl );
		}
	}


	public function webhook() {
		$order = new WC_Order( $_GET['id'] );
		error_log( 'webhook' );
		$res  = file_get_contents( "php://input" );
		$body = json_decode( $res, true );
		error_log( print_r( $body, true ) );
		$headers  = getallheaders();
		$resTime  = $headers['request-time'];
		$clientId = $headers['client-id'];
		if ( $body['result']['resultCode'] == 'SUCCESS' && $order->get_transaction_id() == $body['paymentId'] ) {
			$order->payment_complete();
			wc_reduce_stock_levels( $order->get_id() );
			header( 'response-time', $resTime );
			header( 'client-id', $clientId );
			wp_send_json( [
				'result' => [
					'resultCode'    => 'SUCCESS',
					'resultStatus'  => 'S',
					'resultMessage' => 'Success'
				]
			] );
		}
	}
}
