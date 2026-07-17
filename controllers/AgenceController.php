<?php

class AgenceController {
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
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        $agence_to_edit = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_agence'])) {
                $nom_ville = trim($_POST['nom_ville']);
                if (!empty($nom_ville)) {
                    try {
                        $stmt = $this->pdo->prepare("INSERT INTO agence (nom_ville) VALUES (?)");
                        $stmt->execute([$nom_ville]);
                        $message = "L'agence '" . htmlspecialchars($nom_ville) . "' a été ajoutée avec succès.";
                    } catch (Exception $e) {
                        $error = "Erreur lors de l'ajout : " . $e->getMessage();
                    }
                } else {
                    $error = "Le nom de la ville ne peut pas être vide.";
                }
            }
            
            if (isset($_POST['edit_agence'])) {
                $id_agence = intval($_POST['id_agence']);
                $nom_ville = trim($_POST['nom_ville']);
                if (!empty($nom_ville) && $id_agence > 0) {
                    try {
                        $stmt = $this->pdo->prepare("UPDATE agence SET nom_ville = ? WHERE id_agence = ?");
                        $stmt->execute([$nom_ville, $id_agence]);
                        $message = "L'agence a été modifiée avec succès.";
                        $action = 'list'; 
                    } catch (Exception $e) {
                        $error = "Erreur lors de la modification : " . $e->getMessage();
                    }
                } else {
                    $error = "Le nom de la ville ne peut pas être vide.";
                }
            }
        }

        if (isset($_GET['action'])) {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if ($_GET['action'] === 'delete' && $id > 0) {
                try {
                    $stmt_check = $this->pdo->prepare("SELECT COUNT(*) FROM trajet WHERE id_agence_depart = ? OR id_agence_arrivee = ?");
                    $stmt_check->execute([$id, $id]);
                    $linked_trajets = $stmt_check->fetchColumn();
                    
                    if ($linked_trajets > 0) {
                        $error = "Impossible de supprimer cette agence car elle est associée à $linked_trajets trajet(s).";
                    } else {
                        $stmt = $this->pdo->prepare("DELETE FROM agence WHERE id_agence = ?");
                        $stmt->execute([$id]);
                        $message = "L'agence a été supprimée avec succès.";
                    }
                } catch (Exception $e) {
                    $error = "Erreur lors de la suppression : " . $e->getMessage();
                }
            }
            
            if ($_GET['action'] === 'edit' && $id > 0) {
                $stmt = $this->pdo->prepare("SELECT * FROM agence WHERE id_agence = ?");
                $stmt->execute([$id]);
                $agence_to_edit = $stmt->fetch();
                if ($agence_to_edit) {
                    $action = 'edit';
                }
            }
        }

        try {
            $stmt = $this->pdo->query("SELECT * FROM agence ORDER BY nom_ville ASC");
            $agences = $stmt->fetchAll();
        } catch (Exception $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }

        require_once __DIR__ . '/../views/admin_agences.php';
    }
}