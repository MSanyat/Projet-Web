<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
//use Slim\Route;
use App\Models\Restaurant;
//use App\User;
//use App\Http\Resources\User as UserResource;


// Routes




////////////////////// Traitement de l'ajout de la review
$app->post('/add','RestaurantController:addRestaurant');	
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
	
	$capsule::schema()->dropIfExists('commentaires');
		$capsule::schema()->create('commentaires', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
		$table->integer('id_restaurant');
        $table->string('name');
        $table->longText('comment');
		$table->timestamps();
    });
		echo 'Création de la base';
	});


/////////////////////// Show all 

$app->get('/show-all', 'RestaurantController:showAll') ;
   
////////////////////////// show One restaurant
$app->get("/restaurant/[{id}]",'RestaurantController:showRestaurant');

////////////////////////// Edit One restaurant
$app->get("/edit/[{id}]",'RestaurantController:editRestaurant');

////////////////////// succès d'un edit, on retourne sur le restaurant modifié
$app->post('/edit','RestaurantController:editPostRestaurant');

$app->get('/edit-error',function(Request $request, Response $response,array $args){
	return $this->renderer->render($response,'edit-error.phtml',$args);
});	

////////////////////////// delete One restaurant
$app->get("/delete/[{id}]",'RestaurantController:deleteRestaurant');

//// affichache pour un type de cuisine

$app->get('/type/[{type}]','RestaurantController:showByType');

///// affichage par note

$app->get('/restaurants/by-notes','RestaurantController:showByNote');

///// affichage par prix
$app->get('/restaurants/by-price','RestaurantController:showByPrice');

//// Afficher le formulaire d'ajout de commentaire

$app->get('/add-comment/[{id}]',function(Request $request,Response $response,array $args) {
	$id = $args['id'];
	return $this->renderer->render($response, 'comment-form.phtml', ['id' => $id]);
	
});
///// Ajouter un commentaire

$app->post('/add-comment/[{id}]','CommentaireController:addComment');

//// afficher tous les commentaires
$app->get('/show-comments/[{id}]','CommentaireController:showComments');


/////////////////////// Redirection par défaut
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
	return $response->withRedirect('/show-all');
}); 






















