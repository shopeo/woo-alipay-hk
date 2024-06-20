<?php

namespace Shopeo\WooAlipayHK\Alipay\Request\Auth;

use Shopeo\WooAlipayHK\Alipay\Request\AlipayRequest;

class AlipayAuthRevokeTokenRequest extends AlipayRequest {

	public $accessToken;
	public $extendInfo;

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

	/**
	 * @return mixed
	 */
	public function getExtendInfo() {
		return $this->extendInfo;
	}

	/**
	 * @param mixed $extendInfo
	 */
	public function setExtendInfo( $extendInfo ) {
		$this->extendInfo = $extendInfo;
	}

}
