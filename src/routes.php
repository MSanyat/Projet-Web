<?php

use Slim\Http\Request;
use Slim\Http\Response;
//use Slim\Route;

//use App\User;
//use App\Http\Resources\User as UserResource;


// Routes

/////////////////////// Formulaire d'ajout de review
$app->get('/ajout', function (Request $request, Response $response, array $args) {
   
    // Render index view
    return $this->renderer->render($response, 'form.phtml', $args);
});


////////////////////// Traitement de l'ajout de la review
$app->post('/ajout-reussi', function(Request $request,Response $response,array $args) {
	$name = $request->getParam('name');
	$location=$request->getParam('location');
	$star=$request->getParam('star');
	$type=$request->getParam('type');
	$price=$request->getParam('price');
	$review=$request->getParam('review');

	
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
	$restaurant->save();
	//echo 'Enregistrement effectué'; 

	return $this->renderer->render($response, 'add-success.phtml', $args); 
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
		$table->timestamps();
    });
		echo 'Création de la base';
	});


	
	
/////////////////////// Show all

$app->get('/show-all', function (Request $request, Response $response, array $args) {
   
   
   
		$this->db;
		$restaurants = Restaurant::all();
		
		foreach ($restaurants as $restaurant) {
			//echo $restaurant->name;
		}
		
   
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
$app->post('/edit-success', function(Request $request,Response $response,array $args) {
	$name = $request->getParam('name');
	$location=$request->getParam('location');
	$star=$request->getParam('star');
	$type=$request->getParam('type');
	$price=$request->getParam('price');
	$review=$request->getParam('review');
	$id=$request->getParam('id');

	
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
	
	$restaurant->save();
	//echo 'Enregistrement effectué'; 

	return $this->renderer->render($response, 'restaurant.phtml', ["restaurant" => $restaurant]); 
});




////////////////////////// delete One restaurant
$app->post("/delete-success", function(Request $request,Response $response,array $args){
	
	$this->db;
	
	$id=$request->getParam('id');
	//$id = $args['id'];
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



/////////////////////// main de slim par défaut
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
	
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
	

	
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});







//bonjour personnalisé
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});
















