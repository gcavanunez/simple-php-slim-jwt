<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// $app = new \Slim\App;
$app->get('/api/customers', function (Request $request, Response $response) {

  $sql = "SELECT * FROM customers";
  try {
    //  GET DB Object
    $db = new db();
    $db = $db->connect();
    $stmt = $db->query($sql);
    $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    return $response->withStatus(200)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($customers));
    // echo json_encode($customers);
  } catch (PDOException $e) {
    $error['text'] = $e->getMessage();
    $err['error'] = $error;
    // echo '{"error":{"text": "' . $e->getMessage() . '"}}';
    return $response->withStatus(500)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($err));
  }
  // return $response->withStatus(200)
  //   ->withHeader('Content-Type', 'application/json')
  //   ->write(json_encode($customers));
  // $response->getBody()->write("Customers");

  return $response;
});
$app->get('/api/customer/{id}', function (Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $sql = "SELECT * FROM customers WHERE id = $id";
  try {
    //  GET DB Object
    $db = new db();
    $db = $db->connect();
    $stmt = $db->query($sql);
    $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    return $response->withStatus(200)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($customer));
  } catch (PDOException $e) {
    $error['text'] = $e->getMessage();
    $err['error'] = $error;
    return $response->withStatus(500)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($err));
  }

  return $response;
});
// ADD Customer
$app->post('/api/customer/add', function (Request $request, Response $response) {

  $first_name = $request->getParam('first_name');
  $last_name = $request->getParam('last_name');
  $phone = $request->getParam('phone');
  $email = $request->getParam('email');
  $address = $request->getParam('address');
  $city = $request->getParam('city');
  $state = $request->getParam('state');

  $sql = "INSERT INTO customers (first_name,last_name,phone,email,address,city,state) VALUES (:first_name,:last_name,:phone,:email,:address,:city,:state)";
  try {
    //  GET DB Object
    $db = new db();
    $db = $db->connect();
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state', $state);
    $stmt->execute();
    $msg['text'] = 'Customer added';
    $res['notice'] = $msg;
    return $response->withStatus(200)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($res));
  } catch (PDOException $e) {
    $error['text'] = $e->getMessage();
    $err['error'] = $error;
    return $response->withStatus(500)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($err));
  }

  return $response;
});
// Update Customer
$app->put('/api/customer/update/{id}', function (Request $request, Response $response) {
  $id = $request->getAttribute('id');
  $first_name = $request->getParam('first_name');
  $last_name = $request->getParam('last_name');
  $phone = $request->getParam('phone');
  $email = $request->getParam('email');
  $address = $request->getParam('address');
  $city = $request->getParam('city');
  $state = $request->getParam('state');

  $sql = "UPDATE customers SET
            first_name = :first_name,
            last_name = :last_name,
            phone = :phone,
            email = :email,
            address = :address,
            city = :city,
            state = :state
          WHERE id = $id";
  try {
    //  GET DB Object
    $db = new db();
    $db = $db->connect();
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state', $state);
    $stmt->execute();
    $msg['text'] = 'Customer updated';
    $res['notice'] = $msg;
    return $response->withStatus(200)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($res));
  } catch (PDOException $e) {
    $error['text'] = $e->getMessage();
    $err['error'] = $error;
    return $response->withStatus(500)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($err));
  }

  return $response;
});
// Update Customer
$app->delete('/api/customer/delete/{id}', function (Request $request, Response $response) {
  $id = $request->getAttribute('id');

  $sql = "DELETE FROM customers WHERE id = $id";
  try {
    //  GET DB Object
    $db = new db();
    $db = $db->connect();
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $msg['text'] = 'Customer deleted';
    $res['notice'] = $msg;
    return $response->withStatus(200)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($res));
  } catch (PDOException $e) {
    $error['text'] = $e->getMessage();
    $err['error'] = $error;
    return $response->withStatus(500)
      ->withHeader('Content-Type', 'application/json')
      ->write(json_encode($err));
  }

  return $response;
});