<?php

namespace media\view;
use \media\script\CRSFGuard ;

class ViewFormLog extends ViewMain {

	public function __construct() { 
		parent::__construct();

		$this->layout = 'formLog.html.twig'; 
		$this->arrayVar['traitementForm'] = \Slim\Slim::getInstance()->urlFor('login');

	}
	public function render() {
		$res = parent::render();
		$crsfGuard = new CRSFGuard();
		$res = $crsfGuard::csrfguard_replace_forms($res);

		return $res;
	}
} 