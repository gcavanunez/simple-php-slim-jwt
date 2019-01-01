<?php

$app->add(new \Slim\Middleware\JwtAuthentication([
  "path" => "/api", /* or ["/api", "/admin"] */
  "attribute" => "decoded_token_data",
  "secret" => getenv("JWT_SECRET"),
  "algorithm" => ["HS256"],
  "secure" => false,
  // "callback" => function ($request, $response, $arguments) use ($container) {
  //   $container["jwt"] = $arguments["decoded"];
  // },
  "error" => function ($request, $response, $arguments) {
    $data["status"] = "error";
    $data["message"] = $arguments["message"];
    return $response
      ->withHeader("Content-Type", "application/json")
      ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
  }
]));