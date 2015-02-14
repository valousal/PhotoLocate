<?php

namespace media\view;

class ViewIndex extends ViewMain {

	public function __construct() { 
		parent::__construct();

		$this->layout = 'index.html.twig'; 
		$this->arrayVar['urlFormAddImage'] = \Slim\Slim::getInstance()->urlFor('formAddImage');
		$this->arrayVar['urlFormModifParam'] = \Slim\Slim::getInstance()->urlFor('formParam');
		$this->arrayVar['urlFormAddAdmin'] = \Slim\Slim::getInstance()->urlFor('formAddAdmin');
		$this->arrayVar['logout'] = \Slim\Slim::getInstance()->urlFor('logout');

	}
} 