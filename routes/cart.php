<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// user cart

$app->get('/api/cart/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "SELECT PR.name,PR.image,PR.price,CE.user_id FROM cart_endpoint AS CE
        INNER JOIN product_endpoint AS PR ON CE.product_id = PR.id
        WHERE CE.user_id = $id and CE.add_remove = 'add'";

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

// add to cart

$app->post('/api/cart/add', function (Request $request, Response $response, array $args) {
    $product_id = $request->getParam('product_id');
    $user_id = $request->getParam('user_id');

    $sql = "CALL cart_updater (:product_id, :user_id)";
    // I have opted to use "Add" and "remove" wording so that db administrators can use the datbase for data handling

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

// remove cart item
$app->patch('/api/cart/patch/[{product_id}[/{user_id}]]', function (Request $request, Response $response, array $args) {
    $product_id = $args['product_id'];
    $user_id = $args['user_id'];
    $sql = "UPDATE cart_endpoint SET add_remove = 'remove' WHERE product_id = $product_id and user_id = $user_id";

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
