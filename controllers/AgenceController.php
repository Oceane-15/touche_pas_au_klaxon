<?php
require_once __DIR__ . '/../models/AgenceModel.php';

class AgenceController {
    private AgenceModel $agenceModel;

    public function __construct(PDO $pdo) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $is_admin = isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
        if (!$is_admin) {
            header('Location: /');
            exit();
        }

        $this->agenceModel = new AgenceModel($pdo);
    }

    public function index() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';
        $agence_to_edit = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_agence'])) {
                $nom_ville = trim($_POST['nom_ville']);
                if (!empty($nom_ville)) {
                    try {
                        $this->agenceModel->addAgence($nom_ville);
                        $_SESSION['flash']['success'] = "L'agence '" . htmlspecialchars($nom_ville) . "' a été ajoutée avec succès.";
                    } catch (Exception $e) {
                        $_SESSION['flash']['danger'] = "Erreur lors de l'ajout : " . $e->getMessage();
                    }
                } else {
                    $_SESSION['flash']['danger'] = "Le nom de la ville ne peut pas être vide.";
                }
                header('Location: /admin/agences');
                exit();
            }
            
            if (isset($_POST['edit_agence'])) {
                $id_agence = intval($_POST['id_agence']);
                $nom_ville = trim($_POST['nom_ville']);
                if (!empty($nom_ville) && $id_agence > 0) {
                    try {
                        $this->agenceModel->updateAgence($id_agence, $nom_ville);
                        $_SESSION['flash']['success'] = "L'agence a été modifiée avec succès.";
                    } catch (Exception $e) {
                        $_SESSION['flash']['danger'] = "Erreur lors de la modification : " . $e->getMessage();
                    }
                } else {
                    $_SESSION['flash']['danger'] = "Le nom de la ville ne peut pas être vide.";
                }
                header('Location: /admin/agences');
                exit();
            }
        }

        if (isset($_GET['action'])) {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            
            if ($_GET['action'] === 'delete' && $id > 0) {
                try {
                    $linked_trajets = $this->agenceModel->countLinkedTrajets($id);
                    
                    if ($linked_trajets > 0) {
                        $_SESSION['flash']['danger'] = "Impossible de supprimer cette agence car elle est associée à $linked_trajets trajet(s).";
                    } else {
                        $this->agenceModel->deleteAgence($id);
                        $_SESSION['flash']['success'] = "L'agence a été supprimée avec succès.";
                    }
                } catch (Exception $e) {
                    $_SESSION['flash']['danger'] = "Erreur lors de la suppression : " . $e->getMessage();
                }
                header('Location: /admin/agences');
                exit();
            }
            
            if ($_GET['action'] === 'edit' && $id > 0) {
                $agence_to_edit = $this->agenceModel->getAgenceById($id);
                if ($agence_to_edit) {
                    $action = 'edit';
                }
            }
        }

        try {
            $agences = $this->agenceModel->getAllAgences();
        } catch (Exception $e) {
            die("Erreur de base de données : " . $e->getMessage());
        }

        require_once __DIR__ . '/../views/admin_agences.php';
    }
}