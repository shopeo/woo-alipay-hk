<?php

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
	}


	public function webhook() {

	}
}
