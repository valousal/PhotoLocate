<?php
namespace media\view;

abstract class ViewMain extends View{
	protected $url = null;

	public function __construct(){ 
		$app = new \Slim\Slim();

		// Get request object
		$req = $app->request;

		//Get root URI
		$rootUri = $req->getRootUri();
		//Get resource URI
		//$resourceUri = $req->getResourceUri();

		//Add ROOTURI (les deux méthodes fonctionnent)
		$url = $this->addVar("rootUri", $rootUri);
		//$this->arrayVar['rootUri'] = $rootUri ;

		//Add url for header (bonne méthode?)
		$app = \Slim\Slim::getInstance();
		//index
		/*$index = $app->urlFor('accueil');
		$index = $this->addVar("accueil", $index);*/
		
		//session_start();
		/*if(isset($_SESSION['id'])){
			$authentification = $this->addVar('authentification', $_SESSION);

			//Lien profil Pro
			$urlProfilPro = $app->urlFor('view_profil_pro', array('id' => $_SESSION['id']));
			$urlProfilPro = $this->addVar('view_profil_pro', $urlProfilPro);

			//Logout profil Pro
			$urlLogoutPro = $app->urlFor('LogoutPro');
			$urlLogoutPro = $this->addVar('LogoutPro', $urlLogoutPro);
		}*/
	}

}