<?php

namespace Shopeo\WooAlipayHK\Alipay\Request\Auth;

use Shopeo\WooAlipayHK\Alipay\Request\AlipayRequest;

class AlipayAuthQueryTokenRequest extends AlipayRequest {
	public $accessToken;

	/**
	 * @return mixed
	 */
	public function getAccessToken() {
		return $this->accessToken;
	}

	/**
	 * @param mixed $accessToken
	 */
	public function setAccessToken( $accessToken ) {
		$this->accessToken = $accessToken;
	}

}
