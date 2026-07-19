<?php

class AgenceModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllAgences(): array {
        $stmt = $this->db->query("SELECT * FROM agence ORDER BY nom_ville ASC");
        return $stmt->fetchAll();
    }

    public function getAgenceById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM agence WHERE id_agence = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addAgence(string $nom_ville): bool {
        $stmt = $this->db->prepare("INSERT INTO agence (nom_ville) VALUES (?)");
        return $stmt->execute([$nom_ville]);
    }

    public function updateAgence(int $id, string $nom_ville): bool {
        $stmt = $this->db->prepare("UPDATE agence SET nom_ville = ? WHERE id_agence = ?");
        return $stmt->execute([$nom_ville, $id]);
    }

    public function deleteAgence(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM agence WHERE id_agence = ?");
        return $stmt->execute([$id]);
    }

    public function countLinkedTrajets(int $id_agence): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM trajet WHERE id_agence_depart = ? OR id_agence_arrivee = ?");
        $stmt->execute([$id_agence, $id_agence]);
        return (int)$stmt->fetchColumn();
    }
}