<?php
require_once 'models/TrajetModel.php';

class HomeController {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $is_connected = isset($_SESSION['user_id']);

        $trajetModel = new TrajetModel($this->db);
        $trajets = $trajetModel->getTrajetsDisponibles();

        require_once 'views/accueil.php';
    }
}