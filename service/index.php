<?php
session_start();

require "vendor/autoload.php";
use \media\controller ;
use \media\modele\DataBaseConnect ;
use \media\view ;

$app = new \Slim\Slim(); //Slim init

DataBaseConnect::setConfig("config/config.ini"); 

/*********************************************************************/
/******************************API REST*******************************/
/*********************************************************************/
$app->group('/play', function () use ($app) {
	$app->group('/games', function () use ($app) {
		//Le controleur
		$c = new controller\GameController;
		//Creation d'une partie - données : pseudo utilisateur - retourne id de la partie + un token
		$app->post('/', function() use ($app, $c) {
			$c->InitGame();
		});

		//retourne la description d'une partie : la carte à utiliser pour le positionnement, autres informations utiles
		$app->get('/:id', function($id) use ($app, $c) {
			$c->getGame($id);
		});

		//retourne la liste des 10 photos à placer pour dérouler la partie, avec la position exacte de chaque photo sur la carte
		$app->get('/:id/photos', function($id) use ($app, $c) {
			$c->getPictures($id);
		});

		//mise à jour d'une partie : - score final de l'utilisateur - état de la partie : en cours / terminée.
		$app->put('/:id', function($id) use ($app, $c) {
			$c->putGame($id);
		});

		//Retourne score des parties finies par difficulte
		$app->get('/score/:difficulte', function($difficulte) use ($app, $c) { //ville en param
			$c->getScore($difficulte);
		});
	});
});


//Fonctionnalités pour le module d'administration
$app->group('/admin', function () use ($app) {

		$checkLog = function () use ($app){ //verifie si log ou pas
		    return function()
		    {        
				if (!isset($_SESSION['logged']) || $_SESSION['logged']==false){
					$v = new view\ViewFormLog;
					$v->display();
					exit;
				}
		    };

		};

		//Le controleur
		$c = new controller\AdminController;
		//traitement Ajout d'image + données de localisations
		$app->post('/images', $checkLog(), function() use ($app, $c) {
			$c->postImage();
		})->name('traitementAjoutImage');

		//racine
		$app->get('/', $checkLog(), function() use ($app, $c) {
			$c->displayIndex();
		})->name('index');

		//formulaire ajout images
		$app->get('/formAddImage', $checkLog(), function() use ($app, $c) {
			$c->display_formAddImage();
		})->name('formAddImage');

		//traitement formualire ajout image
		/*$app->post('/formAddImage', function() use ($app, $c) {
			$c->traitementAjoutImage();
		})->name('traitementAjoutImage');*/

		//formulaire modif param
		$app->get('/formParam', $checkLog(), function() use ($app, $c) {
			$c->display_formParam();
		})->name('formParam');

		//traitement modif param
		$app->put('/images',$checkLog(), function() use ($app, $c) {
			$c->putParam();
		})->name('traitementModifParam');

		//formulaire login
		$app->get('/login', function() use ($app, $c) {
			$c->formLog();
		})->name('afficheLogin');

		//traitement login
		$app->post('/login', function() use ($app, $c) {
			$c->traitementLog();
		})->name('login');

		//formulaire ajout admin
		$app->get('/addAdmin', $checkLog(), function() use ($app, $c) {
			$c->formAddAdmin();
		})->name('formAddAdmin');

		//traitement ajout admin
		$app->post('/addAdmin', $checkLog(), function() use ($app, $c) {
			$c->traitementFormAddAdmin();
		})->name('traitementFormAddAdmin');

		//Logout
		$app->get('/logout', function() use ($app, $c){
			$c->logout();
		})->name('logout');
});









$app->run();