<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//$app = AppFactory::create();


// fetch Wishlist Entry by userid

$app->get('/api/wishlist/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "SELECT PR.name,PR.price FROM wishlist_endpoint AS WE
            INNER JOIN product_endpoint AS PR ON WE.product_id = PR.id
            WHERE WE.user_id = $id";

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

$app->post('/api/wishlist/add', function (Request $request, Response $response, array $args) {
    $product_id = $request->getParam('product_id');
    $user_id = $request->getParam('user_id');

    // add stored procedure to limit to 20 additions per user id
    $sql = "INSERT INTO wishlist_endpoint (product_id,user_id) VALUES (:product_id, :user_id)";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':product_id',$product_id);
      $stmt->bindParam(':user_id',$user_id);

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

// delete wishlist item
$app->delete('/api/wishlist/delete/[{id}[/{productid}]]', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $productid = $args['productid'];
    $sql = "DELETE FROM wishlist_endpoint WHERE product_id = $productid AND user_id = $id";

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
