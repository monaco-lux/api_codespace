<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// code to get all products
$app->get('/api/products/all', function (Request $request, Response $response) {
    $sql = "SELECT * FROM product_endpoint";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->query($sql);
      $product = $stmt->fetchAll(PDO::FETCH_OBJ);

      $db = null;
      $response->getBody()->write(json_encode($product));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);
    } catch (PDOException $e)
    {
      $error = ["message" => $e->getMessage()];
      $response->getBody()->write(json_encode($error));
      return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});


 ?>
