<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};


// Service factory for the ORM
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};


//Pass a controller an instance of your table
$container[App\WidgetController::class] = function ($c) {
    $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('db')->table('table_name');
    return new \App\WidgetController($view, $logger, $table);
};
$container['RestaurantController'] = function ($container) {
	return new App\Controllers\RestaurantController($container);
};

$container['CommentaireController'] = function ($container) {
	return new App\Controllers\CommentaireController($container);
};
$container['UserController'] = function ($container) {
	return new App\Controllers\UserController($container);
};
$container['FavoriController'] = function ($container) {
	$collection=new Illuminate\Support\Collection;
	return new App\Controllers\FavoriController($container,$collection);
};
$container['Auth'] = function ($container) {
	return new App\Auth($container);
};
$container['isChecked']= function($container) {
	return App\Auth::check();
};

$container['user']=function($container) {
	return App\Auth::getUser();
};

