<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes



//main de slim par défault
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
	
	//
	spl_autoload_register(function ($classname) {
    require (__DIR__ . '/../src/Models/' . $classname . ".php");
	});
	
	//Establish DB connection
	$this->db;
	
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});



//bonjour personnalisé
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});









