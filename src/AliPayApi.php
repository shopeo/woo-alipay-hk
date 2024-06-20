<?php

namespace Shopeo\WooAlipayHK;

class AliPayApi {
	private $host = 'https://open-sea-global.alipay.com/ams/';
	private $clientId = '';
	private $publicKey = '';
	private $privateKey = '';

	public function __construct( $clientId, $publicKey, $privateKey, $sandbox = true ) {
		$this->clientId   = $clientId;
		$this->publicKey  = $publicKey;
		$this->privateKey = $privateKey;
		if ( $sandbox ) {
			$this->host .= 'sandbox/api/v1/';
		} else {
			$this->host .= 'api/v1/';
		}
	}

	private function signature() {

	}

	private function requestTime() {
		return round( microtime( true ) * 1000 );
	}
}
