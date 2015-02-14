<?php
namespace media\controller ;
use \media\modele\Difficulte ;
use \media\modele\Game ;
use \media\modele\Image ;
use \media\modele\Ville ;
use \media\modele\Admin ;
use \media\view ;
use \media\script\CRSFGuard ;

class AdminController extends AbstractController{
	public function __construct(){

	}
	/******************************/
	/*API function****************/
	/****************************/

	public function postImage(){
		$app = \Slim\Slim::getInstance();
		$rootUri = $app->request->getUrl();
		$rootUri .= $app->request->getRootUri();

		$crsfGuard = new CRSFGuard();
		if ($crsfGuard::csrfguard_validate_token($_POST['CSRFName'], $_POST['CSRFToken'])){
			if (isset($_POST['ville'], $_POST['lat'], $_POST['lng'], $_POST['adresse'], $_POST['nom_img']) && filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT) && filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT) ) {
				$ville = Ville::where("nom", '=', filter_input(INPUT_POST, 'ville', FILTER_SANITIZE_STRING))->first();
				$lng = $_POST['lng'];
				$lat = $_POST['lat'];
				$adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);

				// //Gestion image avec la librairy
				$storage = new \Upload\Storage\FileSystem('src/img');
				$file = new \Upload\File('image', $storage);

				$new_filename = filter_input(INPUT_POST, 'nom_img', FILTER_SANITIZE_STRING);
				$new_filename = str_replace(" ", "_", $new_filename);
				$file->setName($new_filename);

				// // Validate file upload
				// // MimeType List => http://www.webmaster-toolkit.com/mime-types.shtml
				$file->addValidations(array(
				    // Ensure file is of type "image/png"
				  // new \Upload\Validation\Mimetype('image/png'),

				//     //You can also add multi mimetype validation
				    new \Upload\Validation\Mimetype(array('image/png', 'image/gif', 'image/jpg', 'image/jpeg')),

				//     // Ensure file is no larger than 5M (use "B", "K", M", or "G")
				//     new \Upload\Validation\Size('5M')
				));

				// // Access data about the file that has been uploaded
				$data = array(
				    'name'       => $file->getNameWithExtension(),
				    'test'		 => $file->getName(),
				    'extension'  => $file->getExtension(),
				    'mime'       => $file->getMimetype(),
				    'size'       => $file->getSize(),
				    'md5'        => $file->getMd5(),
				    'dimensions' => $file->getDimensions()
				);
				try {
				    // Success!
				   	$file->upload();
				    $image = new Image;
				    //$app->response->headers->set('Content-type','application/json') ;
				    $image->id_ville = $ville->id;
				    $image->titre = $data['test'];
				    $image->extension = ".".$data['extension'];
				    $image->lat = $lat;
				    $image->lng = $lng;
				    $image->adresse = $adresse;
				    $image->date = date("Y-m-d");
				    $image->save();

				    /*$app->response->headers->set('Content-type','application/json') ;
					$app->response->setStatus(201) ;
					echo json_encode(array("données ajoutées"=>$image, "href"=>"$rootUri/src/img/".$image->titre.$image->extension));*/

			    } catch (\Exception $e) {
				    /* Invalid */
			       /* $app->response->headers->set('Content-type','application/json') ;
		           	$app->halt(400, json_encode(array("erreur_message"=>'fail upload image')));*/
		           $v = new view\ViewErreur;
		           $v->display();
		           exit;
				}
			}else{
				// $app->response->headers->set('Content-type','application/json') ;
		  //       $app->halt(400, json_encode(array("erreur_message"=>'Données incorrects')));
		  //       echo "erreur2";
					$v = new view\ViewErreur;
					$v->display();
					exit;
			}

			//$v = new view\ViewRecapAddImage($image);
			$v = new view\ViewIndex;
			$v->display();
		}else{
			$v = new view\ViewErreur;
		    $v->display();
		    exit;
		}
	}

	/*****************************************************************************************************************************************************/

	public function displayIndex(){
		$v = new view\ViewIndex;
		$v->display();
	}

	public function display_formAddImage(){ //rajouter CRFSGuard!!!!!!!!!!!!!!!!!!!!!!
		$arrVilles = Ville::all();
		$v = new view\ViewformAddImage($arrVilles);
		$v->display();
	}

	public function putParam(){
		$app = \Slim\Slim::getInstance();
		$rootUri = $app->request->getUrl();
		$rootUri .= $app->request->getRootUri();
		$crsfGuard = new CRSFGuard();
		if ($crsfGuard::csrfguard_validate_token($_POST['CSRFName'], $_POST['CSRFToken'])){
			if ($app->request->put('difficulte')!= null && $app->request->put('nb_photos')!= null && filter_var($app->request->put('distance'), FILTER_VALIDATE_FLOAT) &&  filter_var($app->request->put('temps'), FILTER_VALIDATE_FLOAT)) {
				$difficulte = difficulte::where("label", '=', filter_var($app->request->put('difficulte'), FILTER_SANITIZE_STRING))->first();
				$difficulte->temps = $app->request->put('temps');
				$difficulte->distance = $app->request->put('distance');
				$difficulte->nb_photos = filter_var($app->request->put('nb_photos'), FILTER_SANITIZE_NUMBER_INT);
				$difficulte->save();
				$v = new view\ViewIndex;
				$v->display();
			}else{
				$v = new view\ViewErreur;
				$v->display();
				exit;
			}
		}else{
			$v = new view\ViewErreur;
			$v->display();
			exit;		
		}
	}

	// public function traitementAjoutImage(){ //rajouter CRFSGuard!!!!!!!!!!!!!!!!!!!!!!
	// 	$app = \Slim\Slim::getInstance();
	// 	$rootUri = $app->request->getUrl();
	// 	$rootUri .= $app->request->getRootUri();

	// 	$client = new \GuzzleHttp\Client();

	// 	if (isset($_POST['ville'], $_POST['lat'], $_POST['lng'], $_POST['adresse']) && filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT) && filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT) ) {
	// 		$ville = $_POST['ville'];
	// 		$lng = $_POST['lng'];
	// 		$lat = $_POST['lat'];
	// 		$adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);

	// 		$storage = new \Upload\Storage\FileSystem('src/img');
	// 		$file = new \Upload\File('image', $storage);
	// 	}

	// 	// Send an asynchronous request.
	// 	//$req = $client->createRequest('POST', "$rootUri./admin/images", ['future' => true]); //, 'ville' => $ville, 'lng' => $lng, 'lat' => $lat/*, 'image' => $file*/
	// 	//$client->send($req)->then(function ($response) {
	// 	//    echo 'I completed! ' . $response;
	// 	//});

	// 	//You can send requests that use a string as the message body.
	// 	//$req = $client->createRequest('POST', "$rootUri./admin/images", ['ville' => $ville, 'lng' => $lng, 'lat' => $lat]);
	// 	//$client->post('/post', ['body' => 'foo']);
	// 	//You can send requests that use a POST body containing fields & files.
	// 	$req = $client->post("$rootUri/admin/images", [
	// 	    'body' => [
	// 	        'ville' => $ville,
	// 	        'lng' => $lng,
	// 	        'lat' => $lat,
	// 	        'adresse' => $adresse,
	// 	        //'image' => fopen('/path/to/file', 'r')
	// 	        'image' => fopen($file, 'r')
	// 	    ]
	// 	]);

	// 	//echo $response = $client->get("$rootUri/play/games/1");

	// 	// print_r(json_decode($req));
	// 	print_r(json_decode($req->getBody()));
	// }

	public function display_formParam(){
		$app = \Slim\Slim::getInstance();
		$rootUri = $app->request->getUrl();
		$rootUri .= $app->request->getRootUri();

		$arrDifficulte = Difficulte::all();

		$v = new view\ViewFormModifParam($arrDifficulte);
		$v->display();
	}

	public function formLog(){
		$app = \Slim\Slim::getInstance();
		$rootUri = $app->request->getUrl();
		$rootUri .= $app->request->getRootUri();

		if (!isset($_SESSION['logged']) || $_SESSION['logged']==false){
			$v = new view\ViewFormLog;
			$v->display();
			exit;
		}else{
			$v = new view\ViewIndex;
			$v->display();
			exit;	
		}		
	}

	public function traitementLog(){
		$crsfGuard = new CRSFGuard();
		if ($crsfGuard::csrfguard_validate_token($_POST['CSRFName'], $_POST['CSRFToken'])){
			if (!isset($_SESSION['logged']) || $_SESSION['logged']==false){
				if (isset($_POST['login'], $_POST['password'])){
					$login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
					$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

					try{
						$admin = Admin::where('login', '=', $login)->first();
						$hash = $admin->password;
						if(password_verify($password, $hash)){
							$_SESSION['id'] = $admin->id;
							$_SESSION['login'] = $admin->login;
							$_SESSION['password'] = $admin->password;
							$_SESSION['logged'] = true;

							$v = new view\ViewIndex;
							$v->display();
						}else{
							$v = new view\ViewErreur;
							$v->display();
						}
					} catch (\Exception $e) {
						$v = new view\ViewErreur;
						$v->display();
					}
				}else{
					$v = new view\ViewErreur;
					$v->display();		
				}
			}else{
				$v = new view\ViewIndex;
				$v->display();
				exit;	
			}
		}else{
			$v = new view\ViewErreur;
			$v->display();
		}
	}

	public function formAddAdmin(){
		$app = \Slim\Slim::getInstance();
		$rootUri = $app->request->getUrl();
		$rootUri .= $app->request->getRootUri();

		$v = new view\ViewFormAdmin;
		$v->display();
	}

	public function traitementFormAddAdmin(){
		$crsfGuard = new CRSFGuard();
		if ($crsfGuard::csrfguard_validate_token($_POST['CSRFName'], $_POST['CSRFToken'])){
			if (isset($_POST['login'], $_POST['password'])){
				$login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
				$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
				$hash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));

				$admin = new Admin;
				$admin->login = $login;
				$admin->password = $hash;
				$admin->save();

				$v = new view\ViewIndex;
				$v->display();
			}else{
				$v = new view\ViewErreur;
				$v->display();		
			}
		}else{
			$v = new view\ViewErreur;
			$v->display();	
		}
	}

	public function logout(){
		unset($_SESSION['id']);
		unset($_SESSION['login']);
		unset($_SESSION['password']);
		unset($_SESSION['logged']);

		$app = \Slim\Slim::getInstance();
		$app->response->redirect($app->urlFor('index'), 303);
	}

}