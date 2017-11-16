<?php 

namespace App\Controllers;

use App\Models\Restaurant as Restaurant;
use App\Models\Commentaire as Commentaire;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;


class RestaurantController {
	
	private $c = null;
	private $renderer;
	private $db;

    public function __construct($container)
    {
        $this->c = $container;
		$this->db=$container->db;
		$this->renderer=$container->renderer;
    }
	
	public function showAll(Request $request,Response $response) {
		
		
		$this->db;
		$restaurants = Restaurant::all();
		
		
   
    // Render view, passe la liste des restos en argument
    return $this->renderer->render($response, 'show-all.phtml', ['restaurants' => $restaurants]);
	
	}
	
	public function addRestaurant(Request $request,Response $response,array $args) {
		if (!empty($request->getParam('name')))	$name = strip_tags($request->getParam('name'));
	if (!empty($request->getParam('location'))) $location=strip_tags($request->getParam('location'));
	if (!empty($request->getParam('star'))) $star=strip_tags($request->getParam('star'));
	if (!empty($request->getParam('type'))) $type=strip_tags($request->getParam('type'));
	if (!empty($request->getParam('price'))) $price=strip_tags($request->getParam('price'));
	if (!empty($request->getParam('review'))) $review=strip_tags($request->getParam('review'));
	// récupérer l'image 
	$uploadedFiles = $request->getUploadedFiles();
	$uploadedFile = $uploadedFiles['file'];
   if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
		// vérification de l'extension du fichier
		 if ($extension =="jpg" || $extension =="png") {
			$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
			$filename = sprintf('%s.%0.8s', $basename, $extension);
			$directory="img";
			$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
			$file=$directory . '/' . $filename;
		}
		else return $response->withRedirect('/add-error');
    }
	else {$file='img/bg-resto-1.jpg';} 
	
if (!empty($name) && !empty($location) && !empty($star) && !empty($type) && !empty($price) && !empty($review)) {	
		//connexion à la bdd
		$this->db;		
		//ajout dans la bdd
		$restaurant=new Restaurant;
		$restaurant->name=$name;
		$restaurant->location=$location;
		$restaurant->rating=$star;
		$restaurant->type=$type;
		$restaurant->price=$price;
		$restaurant->review=$review;
		$restaurant->filepath=$file;
		$restaurant->save();
		//echo 'Enregistrement effectué'; 

		return $this->renderer->render($response, 'add-success.phtml', $args); 
	}
	else return $response->withRedirect('/add-error'.$id);
	}
	
	public function showRestaurant(Request $request,Response $response,array $args) {
		$this->db;
	$id = $args['id'];
	$restaurant = Restaurant::findOrFail($id);
	$comments=Commentaire::where('id_restaurant',$id)->orderBy('created_at','desc')->take(5)->get();
	$nbComments=Commentaire::where('id_restaurant',$id)->count();
	//echo $nbComments;
	return $this->renderer->render ($response, "restaurant.phtml", ["restaurant" => $restaurant,"comments"=>$comments,"nbComments"=>$nbComments]);
	}

	public function editRestaurant(Request $request,Response $response,array $args) {
	$this->db;
	$id = $args['id'];
	$restaurant = Restaurant::findOrFail($id);
	return $this->renderer->render ($response, "edit.phtml", ["restaurant" => $restaurant]);
	}

	public function editPostRestaurant(Request $request,Response $response,array $args) {
	$id=$request->getParam('id');
	if (!empty($request->getParam('name')))	$name = strip_tags($request->getParam('name'));
	if (!empty($request->getParam('location'))) $location=strip_tags($request->getParam('location'));
	if (!empty($request->getParam('star'))) $star=strip_tags($request->getParam('star'));
	if (!empty($request->getParam('type'))) $type=strip_tags($request->getParam('type'));
	if (!empty($request->getParam('price'))) $price=strip_tags($request->getParam('price'));
	if (!empty($request->getParam('review'))) $review=strip_tags($request->getParam('review'));
	
	// récupérer l'image 
	$uploadedFiles = $request->getUploadedFiles();
	$uploadedFile = $uploadedFiles['file'];
   if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
		// vérification de l'extension du fichier
		 if ($extension =="jpg" || $extension =="png") {
			$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
			$filename = sprintf('%s.%0.8s', $basename, $extension);
			$directory="img";
			$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
			$file=$directory . '/' . $filename;
		}
		else return $response->withRedirect('/edit-error');
    }
	else {$file='img/bg-resto-1.jpg';} 
if (!empty($name) && !empty($location) && !empty($star) && !empty($type) && !empty($price) && !empty($review)) {	
	//connexion à la bdd
	$this->db;
	
	//update dans la bdd
	$restaurant= Restaurant::findOrFail($id);
	$restaurant->name=$name;
	$restaurant->location=$location;
	$restaurant->rating=$star;
	$restaurant->type=$type;
	$restaurant->price=$price;
	$restaurant->review=$review;
	$restaurant->filepath=$file;
	
	$restaurant->save();
	//echo 'Enregistrement effectué'; 
}
	//return $this->renderer->render($response, 'restaurant.phtml', ["restaurant" => $restaurant]); 
	return $response->withRedirect('/restaurant/'.$id);  
	}

	public function deleteRestaurant(Request $request,Response $response,array $args) {
		$this->db;
	$id = $args['id'];
	$restaurant = Restaurant::findOrFail($id);
	$restaurant->delete();	
	return $this->renderer->render ($response, "delete-success.phtml");
	}

	public function showByType(Request $request,Response $response,array $args) {
		$this->db;
	$type=mb_strtolower(str_replace(' ','-',$args['type']));
	$restaurants=Restaurant::where('type',$type)->orderBy('name')->get();
	if(count($restaurants)==0) return $response->withRedirect('/'); // si l'utilisateur tape un type non répertorié dans la bare d'url
	else return $this->renderer->render($response, 'show-category.phtml', ['restaurants' => $restaurants]);
	}

	public function showByNote(Request $request,Response $response,array $args) {
	$this->db;
	$restaurants=Restaurant::orderBy('rating','desc')->get();
	if(count($restaurants)==0) return $response->withRedirect('/'); 
	else return $this->renderer->render($response, 'show-category.phtml', ['restaurants' => $restaurants]);
	}

	public function showByPrice(Request $request,Response $response,array $args) {
	$this->db;
	$restaurants=Restaurant::orderBy('price')->get();
	if(count($restaurants)==0) return $response->withRedirect('/'); 
	else return $this->renderer->render($response, 'show-category.phtml', ['restaurants' => $restaurants]);
	}
	
	
	
	
}