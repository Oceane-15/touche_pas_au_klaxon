<?php

class UtilisateurController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->pdo = $pdo;

        $is_admin = isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
        if (!$is_admin) {
            header('Location: /');
            exit();
        }
    }

    public function index() {
        $message = '';
        $error = '';

        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id_to_delete = intval($_GET['id']);
            
            if ($id_to_delete === (int)$_SESSION['user_id']) {
                $error = "Vous ne pouvez pas supprimer votre propre compte administrateur.";
            } else {
                try {
                    $stmt_trajets = $this->pdo->prepare("DELETE FROM trajet WHERE id_utilisateur_auteur = ?");
                    $stmt_trajets->execute([$id_to_delete]);
                    
                    $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
                    $stmt->execute([$id_to_delete]);
                    $message = "L'utilisateur a été supprimé avec succès.";
                } catch (Exception $e) {
                    $error = "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
                }
            }
        }

        try {
            $stmt = $this->pdo->query("SELECT id_utilisateur, nom, prenom, email, telephone, est_admin FROM utilisateur ORDER BY nom ASC");
            $utilisateurs = $stmt->fetchAll();
        } catch (Exception $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }

        require_once __DIR__ . '/../views/admin_utilisateurs.php';
    }
}