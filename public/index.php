<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// use Slim\Http\Request;
// use Slim\Http\Response;
// use \Firebase\JWT\JWT;


require '../vendor/autoload.php';
require '../src/config/db.php';
$dotenv = new Dotenv\Dotenv('../');
$dotenv->load();

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
  $name = $args['name'];
  $response->getBody()->write("Hello, $name");

  return $response;
});

// middlewares routes
require '../src/middlewares/jwt.php';
// auth routes
require '../src/routes/auth.php';
// customer routes
require '../src/routes/customers.php';
$app->run();