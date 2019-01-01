<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// Routes
$app->post('/login', function (Request $request, Response $response, array $args) {

  $input = $request->getParsedBody();
  $sql = "SELECT * FROM users WHERE email= :email";
  $db = new db();
  $db = $db->connect();
  $stmt = $db->prepare($sql);
  $stmt->bindParam("email", $input['email']);
  $stmt->execute();
  $user = $stmt->fetchObject();
 
    // verify email address.
  if (!$user) {
    return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
  }
 
    // verify password.
  if (!password_verify($input['password'], $user->password)) {
    return $this->response->withJson(['error' => true, 'message' => 'Incorrect password']);
  }

  $settings = $this->get('settings'); // get settings array.

  $token = JWT::encode(['id' => $user->id, 'email' => $user->email], getenv("JWT_SECRET"), getenv("JWT_HASH"));

  return $this->response->withJson(['token' => $token]);

});
$app->group('/api', function (\Slim\App $app) {

  $app->get('/user', function (Request $request, Response $response, array $args) {
    print_r($request->getAttribute('decoded_token_data'));
 
        /*output 
        stdClass Object
            (
                [id] => 2
                [email] => arjunphp@gmail.com
            )
                    
     */
  });

});