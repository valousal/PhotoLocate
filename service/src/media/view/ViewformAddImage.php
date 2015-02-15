<?php

namespace media\view;

use \media\script\CRSFGuard ;

class ViewformAddImage extends ViewMain {

	protected $villes;

	public function __construct($villes) { //peut etre faire passer un tableau
		parent::__construct();
		$this->villes = $villes;
		
		$this->layout = 'formAddImage.html.twig'; //mettre document.twig
		$this->arrayVar['traitementForm'] = \Slim\Slim::getInstance()->urlFor('traitementAjoutImage');
		$this->arrayVar['villes'] = $villes;
	}

	public function render() {
		$res = parent::render();
		$crsfGuard = new CRSFGuard();
		$res = $crsfGuard::csrfguard_replace_forms($res);

		return $res;
	}
} 