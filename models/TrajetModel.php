<?php
class TrajetModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Récupère les trajets futurs avec des places disponibles.
     *
     * @return array
     */
    public function getTrajetsDisponibles() {
        $query = "
            SELECT 
                t.id_trajet,
                t.date_heure_depart,
                t.date_heure_arrivee,
                t.places_disponibles,
                t.places_totales,
                t.id_utilisateur_auteur,
                a_depart.nom_ville AS ville_depart,
                a_arrivee.nom_ville AS ville_arrivee,
                u.nom AS auteur_nom,
                u.prenom AS auteur_prenom,
                u.telephone AS auteur_telephone,
                u.email AS auteur_email
            FROM trajet t
            JOIN agence a_depart ON t.id_agence_depart = a_depart.id_agence
            JOIN agence a_arrivee ON t.id_agence_arrivee = a_arrivee.id_agence
            JOIN utilisateur u ON t.id_utilisateur_auteur = u.id_utilisateur
            WHERE t.places_disponibles > 0 
              AND t.date_heure_depart >= NOW()
            ORDER BY t.date_heure_depart ASC
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}