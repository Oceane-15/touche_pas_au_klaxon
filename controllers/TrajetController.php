<?php

class TrajetController {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->pdo = $pdo;

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function proposer() {
        $error_message = "";
        $agences = [];

        try {
            $stmt = $this->pdo->query("SELECT * FROM agence ORDER BY nom_ville ASC");
            $agences = $stmt->fetchAll();
        } catch (Exception $e) {
            $error_message = "Erreur lors du chargement des agences : " . $e->getMessage();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_agence_depart = intval($_POST['id_agence_depart']);
            $id_agence_arrivee = intval($_POST['id_agence_arrivee']);
            $date_heure_depart = $_POST['date_heure_depart'];
            $date_heure_arrivee = $_POST['date_heure_arrivee'];
            $places_totales = intval($_POST['places_totales']);

            if (empty($id_agence_depart) || empty($id_agence_arrivee) || empty($date_heure_depart) || empty($date_heure_arrivee) || empty($places_totales)) {
                $error_message = "Veuillez remplir tous les champs obligatoires.";
            } elseif ($id_agence_depart === $id_agence_arrivee) {
                $error_message = "La ville de départ doit être différente de la ville d'arrivée.";
            } elseif (strtotime($date_heure_depart) >= strtotime($date_heure_arrivee)) {
                $error_message = "La date et heure de départ doivent être antérieures à la date d'arrivée.";
            } elseif ($places_totales <= 0) {
                $error_message = "Le nombre de places totales doit être supérieur à 0.";
            } else {
                try {
                    $sql = "INSERT INTO trajet (date_heure_depart, date_heure_arrivee, places_totales, places_disponibles, id_utilisateur_auteur, id_agence_depart, id_agence_arrivee) 
                            VALUES (:date_depart, :date_arrivee, :places_totales, :places_disponibles, :id_auteur, :id_dep, :id_arr)";
                    
                    $stmt_insert = $this->pdo->prepare($sql);
                    $stmt_insert->execute([
                        ':date_depart'       => $date_heure_depart,
                        ':date_arrivee'      => $date_heure_arrivee,
                        ':places_totales'    => $places_totales,
                        ':places_disponibles'=> $places_totales,
                        ':id_auteur'         => $_SESSION['user_id'],
                        ':id_dep'            => $id_agence_depart,
                        ':id_arr'            => $id_agence_arrivee
                    ]);

                    header('Location: /?success=1');
                    exit;
                } catch (Exception $e) {
                    $error_message = "Impossible d'ajouter le trajet : " . $e->getMessage();
                }
            }
        }

        require_once __DIR__ . '/../views/proposer_trajet.php';
    }
}