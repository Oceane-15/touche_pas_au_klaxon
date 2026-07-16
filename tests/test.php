<?php

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    /** @var PDO */
    private $pdo;

    protected function setUp(): void
    {
        require __DIR__ . '/../includes/db.php';
        
        if (!isset($pdo)) {
            global $pdo;
        }
        
        $this->pdo = $pdo;
    }

    /**
     * Teste l'ajout puis la suppression d'une agence en base de données
     *
     * @return void
     */
    public function testAjouterEtSupprimerAgence(): void
    {
        $this->assertNotNull($this->pdo, "La connexion à la base de données a échoué.");

        $nomVilleTest = "VilleTest_" . uniqid();

        $stmtInsert = $this->pdo->prepare("INSERT INTO agence (nom_ville) VALUES (?)");
        $stmtInsert->execute([$nomVilleTest]);
        $idAgence = $this->pdo->lastInsertId();

        $this->assertGreaterThan(0, $idAgence, "L'agence n'a pas pu être insérée.");

        $stmtCheck = $this->pdo->prepare("SELECT nom_ville FROM agence WHERE id_agence = ?");
        $stmtCheck->execute([$idAgence]);
        $agence = $stmtCheck->fetch();

        $this->assertNotEmpty($agence, "L'agence créée est introuvable.");
        $this->assertEquals($nomVilleTest, $agence['nom_ville'], "Le nom de la ville enregistré ne correspond pas.");

        $stmtDelete = $this->pdo->prepare("DELETE FROM agence WHERE id_agence = ?");
        $stmtDelete->execute([$idAgence]);

        $stmtCheckAgain = $this->pdo->prepare("SELECT COUNT(*) FROM agence WHERE id_agence = ?");
        $stmtCheckAgain->execute([$idAgence]);
        $count = $stmtCheckAgain->fetchColumn();

        $this->assertEquals(0, $count, "L'agence de test n'a pas été supprimée.");
    }

    /**
     * Teste le cycle d'un trajet : création, modification et suppression
     *
     * @return void
     */
    public function testCycleDeVieTrajet(): void
    {
        $this->assertNotNull($this->pdo, "La connexion à la base de données a échoué.");

        $stmtAgence = $this->pdo->prepare("INSERT INTO agence (nom_ville) VALUES (?)");
        
        $stmtAgence->execute(["Depart_" . uniqid()]);
        $idDepart = $this->pdo->lastInsertId();

        $stmtAgence->execute(["Arrivee_" . uniqid()]);
        $idArrivee = $this->pdo->lastInsertId();

        $idAuteur = 1; 
        $dateDepart = "2026-08-01 08:00:00";
        $dateArrivee = "2026-08-01 10:00:00";
        $placesTotales = 4;
        $placesDispo = 4;

        $sqlInsert = "INSERT INTO trajet (date_heure_depart, date_heure_arrivee, places_totales, places_disponibles, id_utilisateur_auteur, id_agence_depart, id_agence_arrivee) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertTrajet = $this->pdo->prepare($sqlInsert);
        $stmtInsertTrajet->execute([$dateDepart, $dateArrivee, $placesTotales, $placesDispo, $idAuteur, $idDepart, $idArrivee]);
        $idTrajet = $this->pdo->lastInsertId();

        $this->assertGreaterThan(0, $idTrajet, "Le trajet n'a pas pu être inséré en base de données.");

        $nouveauPlacesDispo = 2;
        $stmtUpdate = $this->pdo->prepare("UPDATE trajet SET places_disponibles = ? WHERE id_trajet = ?");
        $stmtUpdate->execute([$nouveauPlacesDispo, $idTrajet]);

        $stmtCheck = $this->pdo->prepare("SELECT places_disponibles FROM trajet WHERE id_trajet = ?");
        $stmtCheck->execute([$idTrajet]);
        $trajetModifie = $stmtCheck->fetch();
        
        $this->assertEquals($nouveauPlacesDispo, $trajetModifie['places_disponibles'], "La modification du trajet a échoué.");

        $stmtDeleteTrajet = $this->pdo->prepare("DELETE FROM trajet WHERE id_trajet = ?");
        $stmtDeleteTrajet->execute([$idTrajet]);

        $stmtCheckTrajetAgain = $this->pdo->prepare("SELECT COUNT(*) FROM trajet WHERE id_trajet = ?");
        $stmtCheckTrajetAgain->execute([$idTrajet]);
        $this->assertEquals(0, $stmtCheckTrajetAgain->fetchColumn(), "Le trajet n'a pas été supprimé.");

        $stmtDeleteAgence = $this->pdo->prepare("DELETE FROM agence WHERE id_agence IN (?, ?)");
        $stmtDeleteAgence->execute([$idDepart, $idArrivee]);
    }
}