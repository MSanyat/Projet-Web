<?php	
/**require "../vendor/autoload.php";
$app = new Slim\App();
/**$app->get("/", function() {
echo 'hello world !'; 
$app->get("/hello/{name}", function(Request $request) {
echo 'hello contact';
});
$app->run();
**/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;
/**$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
}); **/
require_once('api/contact.php');
require_once('api/hello.php');

$app->run();

?>