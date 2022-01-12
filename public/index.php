<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = Appfactory::create();

$app->get('/', function(Request $request, Response $response){
  $response->getbody()->write("hello world");
  return $response;
});



 ?>
