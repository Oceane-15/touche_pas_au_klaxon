<?php

class UtilisateurModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllUtilisateurs(): array {
        $stmt = $this->db->query("SELECT id_utilisateur, nom, prenom, email, telephone, est_admin FROM utilisateur ORDER BY nom ASC");
        return $stmt->fetchAll();
    }
}