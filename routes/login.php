<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

/ single entry only

$app->get('/api/login/[{username}[/{password}[/{token}]]]', function (Request $request, Response $response, array $args) {
    $username = $args['username'];
    $password = $args['password'];
    $token = $args['token'];
    $sql = "SELECT * FROM login_endpoint WHERE username = '$username' and password = '$password' and token = '$token'";

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

// add new user

$app->post('/api/login/add', function (Request $request, Response $response, array $args) {
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $token = $request->getParam('token');

    $sql = "INSERT INTO login_endpoint (username,password,token) VALUES (:username, :password, :token)";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':username',$username);
      $stmt->bindParam(':password',$password);
      $stmt->bindParam(':token',$token);

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

// patch username

$app->patch('/api/login/patchuser', function (Request $request, Response $response, array $args) {
    $username = $request->getParam('username');
    $token = $request->getParam('token');

    $sql = "UPDATE login_endpoint SET username = :username WHERE token = :token";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':username',$username);
      $stmt->bindParam(':token',$token);

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

//patch pass

$app->patch('/api/login/patchpass', function (Request $request, Response $response, array $args) {
    $password = $request->getParam('password');
    $token = $request->getParam('token');

    $sql = "UPDATE login_endpoint SET password = :password WHERE token = :token";

    try
    {
      $db = new DB();
      $conn = $db->connect();

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':password',$password);
      $stmt->bindParam(':token',$token);

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


 ?>
