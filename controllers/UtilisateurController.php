<?php
require_once __DIR__ . '/../models/UtilisateurModel.php';

class UtilisateurController {
    private UtilisateurModel $utilisateurModel;

    public function __construct(PDO $pdo) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $is_admin = isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
        if (!$is_admin) {
            header('Location: /');
            exit();
        }

        $this->utilisateurModel = new UtilisateurModel($pdo);
    }

    public function index() {
        $message = '';
        $error = '';

        try {
            $utilisateurs = $this->utilisateurModel->getAllUtilisateurs();
        } catch (Exception $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }

        require_once __DIR__ . '/../views/admin_utilisateurs.php';
    }
}