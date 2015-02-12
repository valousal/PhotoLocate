<?php

namespace media\view;
use \media\script\CRSFGuard ;

class ViewformModifParam extends ViewMain {

   	protected $difficulte;
   
	public function __construct($difficulte) {
		parent::__construct();
		$this->difficulte = $difficulte;
		
		$this->layout = 'formModifParam.html.twig'; //mettre document.twig
		$this->arrayVar['traitementForm'] = \Slim\Slim::getInstance()->urlFor('traitementAjoutImage');
		$this->arrayVar['difficultes'] = $difficulte;
	}

	public function render() {
		$res = parent::render();
		$crsfGuard = new CRSFGuard();
		$res = $crsfGuard::csrfguard_replace_forms($res);

		return $res;
	}

} 