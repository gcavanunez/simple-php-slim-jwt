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

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

$app->add(function ($req, $res, $next) {
  $response = $next($req, $res);
  return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// middlewares routes
require '../src/middlewares/jwt.php';
// auth routes
require '../src/routes/auth.php';
// customer routes
require '../src/routes/customers.php';

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
  $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
  return $handler($req, $res);
});

$app->run();