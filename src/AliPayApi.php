<?php

namespace Shopeo\WooAlipayHK;

use Shopeo\WooAlipayHK\Alipay\DefaultAlipayClient;
use Shopeo\WooAlipayHK\Alipay\Model\Amount;
use Shopeo\WooAlipayHK\Alipay\Model\CustomerBelongsTo;
use Shopeo\WooAlipayHK\Alipay\Model\Env;
use Shopeo\WooAlipayHK\Alipay\Model\Order;
use Shopeo\WooAlipayHK\Alipay\Model\PaymentMethod;
use Shopeo\WooAlipayHK\Alipay\Model\ProductCodeType;
use Shopeo\WooAlipayHK\Alipay\Model\TerminalType;
use Shopeo\WooAlipayHK\Alipay\Request\Pay\AlipayPayRequest;

class AliPayApi {
	private $host = 'https://open-sea-global.alipay.com/';
	private $clientId = '';
	private $publicKey = '';
	private $privateKey = '';
	private $sandbox = true;

	public function __construct( $clientId, $publicKey, $privateKey, $sandbox = true ) {
		$this->clientId   = $clientId;
		$this->publicKey  = $publicKey;
		$this->privateKey = $privateKey;
		$this->sandbox    = $sandbox;
	}

	public function newOrder( $order_id, $currency, $amount, $notify_url, $redirect_url ) {
		$alipayPayRequest = new AlipayPayRequest();
		$path             = $this->sandbox ? '/ams/sandbox/api/v1/payments/pay' : '/ams/api/v1/payments/pay';

		$paymentMethod = new PaymentMethod();
		$paymentMethod->setPaymentMethodType( CustomerBelongsTo::ALIPAY_HK );
		$alipayPayRequest->setPaymentMethod( $paymentMethod );

		$order = new Order();
		$order->setOrderDescription( 'Online buy' );
		$order->setReferenceOrderId( $order_id );

		$orderAmount = new Amount();
		$orderAmount->setCurrency( $currency );
		$orderAmount->setValue( floatval( $amount ) * 100 );
		$order->setOrderAmount( $orderAmount );

		$env = new Env();
		$env->setTerminalType( TerminalType::WEB );
		$order->setEnv( $env );

		$alipayPayRequest->setClientId( $this->clientId );
		$alipayPayRequest->setPath( $path );
		$alipayPayRequest->setProductCode( ProductCodeType::CASHIER_PAYMENT );
		$alipayPayRequest->setPaymentRequestId( $order_id );
		$alipayPayRequest->setPaymentAmount( $orderAmount );
		$alipayPayRequest->setOrder( $order );
		$alipayPayRequest->setPaymentNotifyUrl( $notify_url );
		$alipayPayRequest->setPaymentRedirectUrl( $redirect_url );
		$alipayClient = new DefaultAlipayClient( $this->host, $this->privateKey, $this->publicKey );
		try {
			return $alipayClient->execute( $alipayPayRequest );
		} catch ( \Exception $exception ) {
			error_log( 'Alipay Error!' );
		}
	}
}
