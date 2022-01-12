<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// code to get all products
$app->get('/api/products/all', function (Request $request, Response $response) {
    // sql statement to get all products
    $sql = "SELECT * FROM product_endpoint";


    // connection section with incorporated error code checking
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

// single entry only

$app->get('/api/products/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "SELECT * FROM product_endpoint WHERE id = $id";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->query($sql);
      $product = $stmt->fetch(PDO::FETCH_OBJ);

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

// add products

$app->post('/api/products/add', function (Request $request, Response $response, array $args) {
    $name = $request->getParam('name');
    $description = $request->getParam('description');
    $image = $request->getParam('image');
    $price = $request->getParam('price');
    $category_id = $request->getParam('category_id');

    $sql = "INSERT INTO product_endpoint (name,description,image,price,category_id) VALUES (:name, :description, :image, :price, :category_id)";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':name',$name);
      $stmt->bindParam(':description',$description);
      $stmt->bindParam(':image',$image);
      $stmt->bindParam(':price',$price);
      $stmt->bindParam(':category_id',$category_id);

      $result = $stmt->execute();

      $db = null;
      $response->getBody()->write(json_encode($result));
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

// delete product
$app->delete('/api/products/delete/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "DELETE FROM product_endpoint WHERE id = $id";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();

      $db = null;
      $response->getBody()->write(json_encode($result));
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


/* Note for people reading my commit: I originally created this project
under the heading "online_store_backend", which is on my github, however
I managed to mess up the connection and I wasn't able to restore it. The code
above is from that project. I am merely restoring it here as it now works. 
*/


 ?>
