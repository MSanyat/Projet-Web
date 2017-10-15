<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

//Formulaire
$app->get('/Ajout', function (Request $request, Response $response, array $args) {

   
	spl_autoload_register(function ($classname) {
    require (__DIR__ . '/../src/Models/' . $classname . ".php");
	});
	
	
	//Establish DB connection, see settings.php for databse configuration settings (password...)
	$this->db;
	
	// https://laravel.com/docs/4.2/schema
	$capsule = new \Illuminate\Database\Capsule\Manager;

	//a changer avec un if else pour créer la table que si elle n'existe pas
    $capsule::schema()->dropIfExists('restaurant');

    $capsule::schema()->create('restaurant', function (\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->integer('rating')->default(0);
        $table->integer('type')->default(1);
        $table->integer('price')->default(1);
        $table->string('location');
        $table->longText('review');
    });

	
   
    // Render index view
    return $this->renderer->render($response, 'form.phtml', $args);
});





// ajout d'une route test
$app->get('/test',function(Request $request, Response $response){
	echo "ceci est un test";
}); 

//main de slim par défault
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
















