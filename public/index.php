<?php

use App\Models\Db;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello World!');
    return $response;
});

$app->get('/test', function (Request $request, Response $response) {
    $response->getBody()->write("Test");
    return $response;
});

$app->get('/customers-data/all', function (Request $request, Response $response) {
    $sql = "SELECT * FROM customers";

    try {
        $db = new Db();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->post('/customers-data/add', function (Request $request, Response $response) {

    $data = $request->getParsedBody();
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];

    $sql = "insert into customers(name, email, phone) values(:name, :email, :phone)";

    try {
        $db = new Db();
        $conn = $db->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        $customers = $stmt->execute();
        $db = null;

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});


$app->put('/customers-data/update/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];

    $sql = "update customers set name=:name, email=:email, phone=:phone where id = $id";

    try {
        $db = new Db();
        $conn = $db->connect();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        $customers = $stmt->execute();
        $db = null;

        echo "Update!";

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->delete('/customers-data/delete/{id}', function (Request $request, Response $response) {

    $id = $request->getAttribute('id');
    $data = $request->getParsedBody();
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];

    $sql = "delete from customers where id = $id";

    try {
        $db = new Db();
        $conn = $db->connect();
        $stmt = $conn->prepare($sql);
        $customers = $stmt->execute();
        $db = null;

        echo "Delete!";

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->run();
