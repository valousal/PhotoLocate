<?php

namespace media\view;

class ViewErreur extends ViewMain {

   /*protected $obj2;
   protected $obj4;*/
	public function __construct(/*$d,$x,$z,$e*/) { //peut etre faire passer un tableau
		parent::__construct();
		//$this->obj2 = $x;
		//$this->obj4 = $z;
		
		$this->layout = 'erreur.html.twig'; //mettre document.twig
		$this->arrayVar['index'] = \Slim\Slim::getInstance()->urlFor('index');
	}
} 