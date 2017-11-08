<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
//use Slim\Route;

//use App\User;
//use App\Http\Resources\User as UserResource;


// Routes




////////////////////// Traitement de l'ajout de la review
$app->post('/ajout-reussi', function(Request $request,Response $response,array $args) {
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
		$basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
		$filename = sprintf('%s.%0.8s', $basename, $extension);
		$directory="img";
		$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
     //   $response->write('uploaded ' . $filename . '<br/>');
		$file=$directory . '/' . $filename;
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
});	
$app->get('/add-error',function(Request $request, Response $response,array $args){
	return $this->renderer->render($response,'add-error.phtml',$args);
});	
/////////////////////// Formulaire d'ajout de review

$app->get('/add',function(Request $request, Response $response,array $args){
	return $this->renderer->render($response,'form.phtml',$args);
}); 

////////////////////// route création bdd
$app->get('/installbdd',function(Request $request, Response $response){
		//Establish DB connection, see settings.php for databse configuration settings (password...)
	$this->db;
	
	// https://laravel.com/docs/4.2/schema
	$capsule = new \Illuminate\Database\Capsule\Manager;

	//a changer avec un if else pour créer la table que si elle n'existe pas
    

    
		$capsule::schema()->dropIfExists('restaurant');
		$capsule::schema()->create('restaurant', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->integer('rating')->default(1);
        $table->string('type');
        $table->integer('price')->default(1);
        $table->string('location');
        $table->longText('review');
		$table->string('filepath');
		$table->timestamps();
    });
		echo 'Création de la base';
	});


	
	
/////////////////////// Show all 

$app->get('/show-all', function (Request $request, Response $response, array $args) {
   
   
   
		$this->db;
		$restaurants = Restaurant::all();
		
		
   
    // Render view, passe la liste des restos en argument
    return $this->renderer->render($response, 'show-all.phtml', ['restaurants' => $restaurants]);
});


////////////////////////// show One restaurant
$app->get("/restaurant/[{id}]", function(Request $request,Response $response,array $args){
	
	$this->db;
	$id = $args['id'];
	$restaurant = Restaurant::findOrFail($id);
	return $this->renderer->render ($response, "restaurant.phtml", ["restaurant" => $restaurant]);

});




////////////////////////// Edit One restaurant
$app->get("/edit/[{id}]", function(Request $request,Response $response,array $args){
	
	$this->db;
	$id = $args['id'];
	$restaurant = Restaurant::findOrFail($id);
	return $this->renderer->render ($response, "edit.phtml", ["restaurant" => $restaurant]);

});




////////////////////// succès d'un edit, on retourne sur le restaurant modifié
$app->post('/edit', function(Request $request,Response $response,array $args) {
	$name = $request->getParam('name');
	$location=$request->getParam('location');
	$star=$request->getParam('star');
	$type=$request->getParam('type');
	$price=$request->getParam('price');
	$review=$request->getParam('review');
	$id=$request->getParam('id');
	
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
			echo $extension;
		}
		else return $response->withRedirect('/edit-error');
    }
	else {$file='img/bg-resto-1.jpg';} 
	
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

	//return $this->renderer->render($response, 'restaurant.phtml', ["restaurant" => $restaurant]); 
	return $response->withRedirect('/restaurant/'.$id); 
});

$app->get('/edit-error',function(Request $request, Response $response,array $args){
	return $this->renderer->render($response,'edit-error.phtml',$args);
});	


////////////////////////// delete One restaurant
$app->get("/delete/[{id}]", function(Request $request,Response $response,array $args){
	
	$this->db;
	
	//$id=$request->getParam('id');
	$id = $args['id'];
	$restaurant = Restaurant::findOrFail($id);
	$restaurant->delete();
	
	
	//$restaurants = Restaurant::all();
		
	
	
	return $this->renderer->render ($response, "delete-success.phtml", $args /*["restaurants" => $restaurants]*/);

});





////////////////////// ajout d'une route test
$app->get('/test',function(Request $request, Response $response){
	echo "ceci est un test";
}); 
$app->get('/resto',function(Request $request, Response $response) {
	$this->db;
	$restaurants = Restaurant::all();
		
		foreach($restaurants as $restaurant) {
			echo $restaurant->name;
		}
	
});



/////////////////////// Redirection par défaut
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
/**	
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
	

	
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args); **/
	return $response->withRedirect('/show-all');
}); 







//bonjour personnalisé
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});
















