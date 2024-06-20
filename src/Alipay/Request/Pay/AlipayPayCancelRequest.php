<?php

namespace Shopeo\WooAlipayHK\Alipay\Request\Pay;

use Shopeo\WooAlipayHK\Alipay\Request\AlipayRequest;

class AlipayPayCancelRequest extends AlipayRequest {

	public $paymentId;
	public $paymentRequestId;

	/**
	 * @return mixed
	 */
	public function getPaymentId() {
		return $this->paymentId;
	}

	/**
	 * @param mixed $paymentId
	 */
	public function setPaymentId( $paymentId ) {
		$this->paymentId = $paymentId;
	}

	/**
	 * @return mixed
	 */
	public function getPaymentRequestId() {
		return $this->paymentRequestId;
	}

	/**
	 * @param mixed $paymentRequestId
	 */
	public function setPaymentRequestId( $paymentRequestId ) {
		$this->paymentRequestId = $paymentRequestId;
	}

}
