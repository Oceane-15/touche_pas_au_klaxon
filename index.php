<?php
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/db.php'; 
/** @var PDO $pdo */

use Buki\Router\Router;

$router = new Router();

$router->get('/', function() use ($pdo) { 
    require_once __DIR__ . '/controllers/HomeController.php';
    $controller = new HomeController($pdo);
    $controller->index();
});

$router->get('/login', function() use ($pdo) {
    require_once __DIR__ . '/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->login();
});

$router->post('/login', function() use ($pdo) {
    require_once __DIR__ . '/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->login();
});

$router->get('/logout', function() use ($pdo) {
    require_once __DIR__ . '/controllers/AuthController.php';
    $controller = new AuthController($pdo, false);
    $controller->logout();
}); 
    

$router->get('/trajets', function() use ($pdo) {
    require_once __DIR__ . '/controllers/TrajetController.php';
    $controller = new TrajetController($pdo);
    $controller->proposer();
});
$router->post('/trajets', function() use ($pdo) {
    require_once __DIR__ . '/controllers/TrajetController.php';
    $controller = new TrajetController($pdo);
    $controller->proposer();
});

$router->get('/trajet/modifier', function() use ($pdo) {
    require_once __DIR__ . '/controllers/TrajetController.php';
    $controller = new TrajetController($pdo);
    $controller->edit();
});

$router->post('/trajet/modifier', function() use ($pdo) {
    require_once __DIR__ . '/controllers/TrajetController.php';
    $controller = new TrajetController($pdo);
    $controller->edit();
});

$router->get('/trajet/supprimer', function() use ($pdo) {
    require_once __DIR__ . '/controllers/TrajetController.php';
    $controller = new TrajetController($pdo);
    $controller->delete();
});

$router->any('/admin/agences', function() use ($pdo) {
    require_once __DIR__ . '/controllers/AgenceController.php';
    $controller = new AgenceController($pdo);
    $controller->index();
});

$router->get('/admin/utilisateurs', function() use ($pdo) {
    require_once __DIR__ . '/controllers/UtilisateurController.php';
    $controller = new UtilisateurController($pdo);
    $controller->index();
});

$router->run();