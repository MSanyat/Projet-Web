<?php	
require "../vendor/autoload.php";
$app = new Slim\App();
$app->get("/", function() {
echo 'hello world !!';
});
$app->run();

?>