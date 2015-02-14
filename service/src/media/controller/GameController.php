<?php
namespace media\controller ;
use \media\modele\Difficulte ;
use \media\modele\Game ;
use \media\modele\Image ;
use \media\modele\Ville ;

class GameController extends AbstractController{
	public function __construct(){

	}

	//verifie le token avec l'id de la partie, à appeler dans les fonctions
	public function checkToken($id, $token){
		$game1 = Game::find($id);
		$game2 = Game::where('token','=', $token)->first();

		if ($game1->token == $token && $game2->id == $id){
			return true;
		}else{
			return false;
		}
	}

	//Creation d'une partie - données : pseudo utilisateur - retourne id de la partie + un token
	public function InitGame(){
		//GENERATE TOKEN
		$token = "";

		// Définir tout les caractères possibles dans le mot de passe, 
		// Il est possible de rajouter des voyelles ou bien des caractères spéciaux
		$possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
		$longueur = 30;
		// obtenir le nombre de caractères dans la chaîne précédente
		// cette valeur sera utilisé plus tard
		$longueurMax = strlen($possible);

		if ($longueur > $longueurMax) {
			$longueur = $longueurMax;
		}

		// initialiser le compteur
		$i = 0;

		// ajouter un caractère aléatoire à $mdp jusqu'à ce que $longueur soit atteint
		while ($i < $longueur) {
			// prendre un caractère aléatoire
			$caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);

			// vérifier si le caractère est déjà utilisé dans $mdp
			if (!strstr($token, $caractere)) {
				// Si non, ajouter le caractère à $mdp et augmenter le compteur
				$token .= $caractere;
				$i++;
			}
		}

		$app = \Slim\Slim::getInstance();
		$params = $app->request->getBody();
		$rep = json_decode($params,true);
		// $p = $rep['player']; 
		// $l = $rep['level'];
		// $v = $rep['ville'];

		if(isset($rep['player'], $rep['level'], $rep['ville'])){

			$p = $rep['player']; 
			$l = $rep['level'];
			$v = $rep['ville'];

			$ville = filter_var($v, FILTER_SANITIZE_STRING);
			$player = filter_var($p, FILTER_SANITIZE_STRING);
			$level = filter_var($l, FILTER_SANITIZE_STRING);
			$ville = Ville::where('nom','=',$ville)->first();	
			$level = Difficulte::where('label','=',$level)->first();

			$newGame = new Game;
			$newGame->player = $player;
			$newGame->id_ville = $ville->id; 
			$newGame->id_difficulte = $level->id;
			$newGame->date = date('Y-m-d');
			$newGame->status = 'Playing';
			$newGame->score = 0;
			$newGame->token = $token;
			$newGame->save();

			//Insertion du toker en session
			//$_SESSION['token'] = $token;


			//Renvoi les données en JSON id de la partie et le token
			$app = \Slim\Slim::getInstance();
			$app->response->setStatus(201) ;
			$app->response->headers->set('Content-type','application/json') ;
			//echo json_encode(array("games"=>$newGame->id, "token" => $_SESSION['token']));
			// $newGame->token = $_SESSION['token'];
			// echo $newGame->toJson();
			//$arr = array('id' => $newGame->id, 'token' => $_SESSION['token']);
			$arr = array('id' => $newGame->id, 'token' => $newGame->token);
			echo json_encode($arr);
		}else{
			$app->response->setStatus(401) ;
			$app->response->headers->set('Content-type','application/json') ;
			echo json_encode(array("error_message"=>"champs invalides"));
		}
	}

	//retourne info partie
	public function getGame($id){
		$app = \Slim\Slim::getInstance();
		$game = Game::find($id);

		//verif token and id
		$token = null;
		if(isset($_GET['apiKey']))
		$token = filter_var($_GET['apiKey'], FILTER_SANITIZE_STRING);
		if (!self::checkToken($id, $token)){
			$app->response->headers->set('Content-type','application/json') ;
			$app->halt(401, json_encode(array("erreur_message"=>'invalide APIKey')));
		}

		$ville = $game->ville;
		$difficulte = $game->difficulte;
		$images = $ville->images()->get();
		$nbImages = count($images);


		$res = array("player"=>$game->player, "difficulte"=>array("label"=>$difficulte->label, "distance"=>$difficulte->distance, "temps"=>$difficulte->temps, "nb_photos"=>$difficulte->nb_photos), "score"=>$game->score,"nbImages" =>$nbImages,
			"ville"=>array("nom"=>$ville->nom, "lat"=>$ville->lat, "lng"=>$ville->lng)
			);

		$app->response->setStatus(200) ;
		$app->response->headers->set('Content-type','application/json') ;
		echo json_encode($res);

	}

	//retourne la liste des 10photos 
	public function getPictures($id){
		$app = \Slim\Slim::getInstance();

		$game = Game::find($id);

		//verif token and id
		$token = null;
		if(isset($_GET['apiKey']))
		$token = filter_var($_GET['apiKey'], FILTER_SANITIZE_STRING);
		if (!self::checkToken($id, $token)){
			$app->response->headers->set('Content-type','application/json') ;
			$app->halt(401, json_encode(array("erreur_message"=>'invalide APIKey')));
		}

		$ville = $game->ville;
		$images = $ville->images()->orderByRaw('RAND()')->get(); //rajouter un champs description photo dans la BDD?

		$imageAndLinks = array();

		$rootUri = $app->request->getUrl();
		$rootUri .= $app->request->getRootUri();

		foreach ($images as $image){
			$path = "$rootUri/src/img/".$image->titre.$image->extension; //ou le foutre direct dans la BDD, au choix
			$imageAndLinks[] = array("image"=>$image, "href"=>$path);
		}
 
		$app->response->setStatus(200) ;
		$app->response->headers->set('Content-type','application/json') ;
		echo json_encode($imageAndLinks);
	}

	//modifie une partie
	public function putGame($id){
		$app = \Slim\Slim::getInstance();

		/*//verif token and id
		$token = null;
		if(isset($_GET['apiKey']))
		$token = filter_var($_GET['apiKey'], FILTER_SANITIZE_STRING);
		if (!self::checkToken($id, $token)){
			$app->response->headers->set('Content-type','application/json') ;
			$app->halt(401, json_encode(array("erreur_message"=>'invalide APIKey')));
		}

		if((null != $app->request->put('status')) && filter_var($app->request->put('score'), FILTER_VALIDATE_FLOAT)) {
			$status = filter_var($app->request->put('status'), FILTER_SANITIZE_STRING);
			$score = $app->request->put('score');

			$game = Game::find($id);
			$game->status = $status;
			$game->score = $score;
			$game->save();

			$rootUri = $app->request->getUrl();
			$rootUri .= $app->request->getRootUri();
			$app->response->headers->set('Content-type','application/json') ;
			$app->response->setStatus(200) ;
			echo json_encode(array("données modifiées"=>array("score"=>$score, "status"=>$status), "href"=>"$rootUri/play/games/$id"));

		}else{
			$app->response->headers->set('Content-type','application/json') ;
	        $app->halt(400, json_encode(array("erreur_message"=>'champs manquants ou invalide')));
		}*/

		$data = $app->request->getBody();
		$data = json_decode($data, true);
		if(isset($_GET['apiKey']))
		$token = filter_var($_GET['apiKey'], FILTER_SANITIZE_STRING);
		if (!self::checkToken($id, $token)){
			$app->response->headers->set('Content-type','application/json') ;
			$app->halt(401, json_encode(array("erreur_message"=>'invalide APIKey')));
		}

		if((null != $data['status']) && filter_var($data['score'], FILTER_VALIDATE_FLOAT)) {
			$status = filter_var($data['status'], FILTER_SANITIZE_STRING);
			$score = $data['score'];

			$game = Game::find($id);
			$game->status = $status;
			$game->score = $score;
			$game->save();

			$rootUri = $app->request->getUrl();
			$rootUri .= $app->request->getRootUri();
			$app->response->headers->set('Content-type','application/json') ;
			$app->response->setStatus(200) ;
			echo json_encode(array("données modifiées"=>array("score"=>$score, "status"=>$status), "href"=>"$rootUri/play/games/$id"));

		}else{
			$app->response->headers->set('Content-type','application/json') ;
	        $app->halt(400, json_encode(array("erreur_message"=>'champs manquants ou invalide')));
		}
	}

	//retourne classement 10 meilleurs
	public function getScore($difficulte){
		$app = \Slim\Slim::getInstance();

		if(isset($_GET['ville']) && $_GET['ville']!= ""){
			$villeName = filter_var($_GET['ville'], FILTER_SANITIZE_STRING);
			$ville = Ville::where("nom", "=", $villeName)->first();
			$difficulte = filter_var($difficulte, FILTER_SANITIZE_STRING);
			$difficulte = Difficulte::where('label', '=', $difficulte)->first();
			$games = Game::orderBy('score', 'desc')->take(10)->whereRaw('status = ? and id_difficulte = ? and id_ville = ?', array('finish', $difficulte->id, $ville->id))->get();
		}else{
			$difficulte = filter_var($difficulte, FILTER_SANITIZE_STRING);
			$difficulte = Difficulte::where('label', '=', $difficulte)->first();
			$games = Game::orderBy('score', 'desc')->take(10)->whereRaw('status = ? and id_difficulte = ?', array('finish', $difficulte->id))->get();
			/*$app->response->headers->set('Content-type','application/json') ;
			$app->halt(500, json_encode(array("erreur_message"=>'le parametre ville.name est absent')));*/
		}

		$arrRes = array();
			foreach ($games as $game){
				$arrRes[] = array("player"=> $game->player,
									"score"=> $game->score,
									"date" => $game->date,
									"difficulte" => $difficulte->label
					);
			}
		$app->response->headers->set('Content-type','application/json') ;
		$app->response->setStatus(200) ;
		echo json_encode($arrRes);
	}

}	