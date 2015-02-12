<?php

namespace media\view;

class ViewRecapAddImage extends ViewMain {

   protected $image;

	public function __construct($image) { //peut etre faire passer un tableau
		parent::__construct();
		$this->image = $image;
		
		$this->layout = 'recapAddImage.html.twig'; //mettre document.twig
		$this->arrayVar['image'] = $image;
	}
} 