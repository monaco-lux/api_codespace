<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
// call database
require __DIR__ . '/../config/db.php';

$app = AppFactory::create();

// product route
require __DIR__ . '/../routes/product.php';

$app->run();

 ?>
