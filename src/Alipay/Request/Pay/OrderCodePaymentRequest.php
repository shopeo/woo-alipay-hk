<?php

namespace Shopeo\WooAlipayHK\Alipay\Request\Pay;

use Exception;
use Shopeo\WooAlipayHK\Alipay\Model\Amount;
use Shopeo\WooAlipayHK\Alipay\Model\InStorePaymentScenario;
use Shopeo\WooAlipayHK\Alipay\Model\PaymentFactor;
use Shopeo\WooAlipayHK\Alipay\Model\PaymentMethod;
use Shopeo\WooAlipayHK\Alipay\Model\ProductCodeType;

class OrderCodePaymentRequest extends AlipayPayRequest {


	function __construct( $paymentRequestId, $order, $currency, $amountInCents, $paymentNotifyUrl, $paymentExpiryTime ) {
		$this->setPath( '/ams/api/v1/payments/Pay' );
		$this->setProductCode( ProductCodeType::IN_STORE_PAYMENT );

		$paymentAmount = new Amount();
		$paymentAmount->setCurrency( $currency );
		$paymentAmount->setValue( $amountInCents );
		$this->setPaymentAmount( $paymentAmount );

		$paymentMethod = new PaymentMethod();
		$paymentMethod->setPaymentMethodType( 'CONNECT_WALLET' );
		$this->setPaymentMethod( $paymentMethod );

		$paymentFactor = new PaymentFactor();
		$paymentFactor->setInStorePaymentScenario( InStorePaymentScenario::OrderCode );
		$this->setPaymentFactor( $paymentFactor );

		$this->setPaymentRequestId( $paymentRequestId );
		$this->setOrder( $order );

		if ( isset( $paymentNotifyUrl ) ) {
			$this->setPaymentNotifyUrl( $paymentNotifyUrl );
		}

		if ( isset( $paymentExpiryTime ) ) {
			$this->setPaymentExpireTime( $paymentExpiryTime );
		}

	}

	function validate() {
		$this->assertTrue( isset( $this->order ), "Order required." );
		$this->assertTrue( isset( $this->order->merchant ), "Order.Merchant required." );
		$this->assertTrue( isset( $this->order->orderAmount ), "Order.orderAmount required." );
		$this->assertTrue( isset( $this->order->orderDescription ), "Order.orderDescription required." );
		$this->assertTrue( isset( $this->order->merchant->referenceMerchantId ), "Order.Merchant.referenceMerchantId required." );
		$this->assertTrue( isset( $this->order->merchant->merchantMCC ), "Order.Merchant.merchantMcc required." );
		$this->assertTrue( isset( $this->order->merchant->merchantName ), "Order.Merchant.merchantName required." );
		$this->assertTrue( isset( $this->order->merchant->store ), "Order.Merchant.store required." );
		$this->assertTrue( isset( $this->order->merchant->store->referenceStoreId ), "Order.Merchant.store.referenceStoreId required." );
		$this->assertTrue( isset( $this->order->merchant->store->storeName ), "Order.Merchant.store.storeName required." );
		$this->assertTrue( isset( $this->order->merchant->store->storeMCC ), "Order.Merchant.store.storeMcc required." );
	}

	function assertTrue( $exp, $msg ) {
		if ( ! $exp ) {
			throw new Exception( $msg );
		}
	}

}
