<?php

use Wikia\Service\Gateway\KubernetesExternalUrlProvider;

class AutoLoginService extends WikiaService {

	private $kubernetesExternalUrlProvider;

	const SYNC_COOKIE_NAME = 'autologin_done';

	public static function cookieSyncEnabled( WebRequest $request ) {
		return $request->getCookie( self::SYNC_COOKIE_NAME, "" ) !== '1';
	}

	public function __construct() {
		parent::__construct();
		$this->kubernetesExternalUrlProvider = new KubernetesExternalUrlProvider();
	}

	public function index() {
		$this->setVal( 'url', $this->getServiceUrl() );
	}

	public function passive() {
		$this->setVal( 'url', $this->getServicePassiveUrl() );
	}

	protected function getServicePassiveUrl() {
		return $this->kubernetesExternalUrlProvider->getAlternativeUrl( 'autologin' ) . '/passive_frame';
	}

	protected function getServiceUrl() {
		return $this->kubernetesExternalUrlProvider->getUrl( 'autologin' ) . '/frame';
	}
}
