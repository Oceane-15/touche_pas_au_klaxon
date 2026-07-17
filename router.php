<?php
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;
}
require_once __DIR__ . '/vendor/autoload.php';

use Buki\Router\Router;

$router = new Router();

$router->get('/', function() {
    require_once __DIR__ . '/index.php';
});

$router->get('/login', function() {
    require_once __DIR__ . '/login.php';
});
$router->post('/login', function() {
    require_once __DIR__ . '/login.php';
});
$router->get('/logout', function() {
    require_once __DIR__ . '/logout.php';
});

$router->get('/trajets', function() {
    require_once __DIR__ . '/trajets.php';
});
$router->post('/trajets', function() {
    require_once __DIR__ . '/trajets.php';
});
$router->any('/trajet/modifier', function() {
    require_once __DIR__ . '/modif_trajet.php';
});
$router->get('/trajet/supprimer', function() {
    require_once __DIR__ . '/supprimer_trajet.php';
});

$router->any('/admin/agences', function() {
    require_once __DIR__ . '/admin_agences.php';
});
$router->get('/admin/utilisateurs', function() {
    require_once __DIR__ . '/admin_utilisateurs.php';
});

$router->run();