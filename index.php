<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    $query_trajets = "
        SELECT 
            t.id_trajet,
            t.date_heure_depart,
            t.places_disponibles,
            u.nom AS conducteur_nom,
            u.prenom AS conducteur_prenom,
            a_depart.nom_ville AS ville_depart,
            a_arrivee.nom_ville AS ville_arrivee
        FROM trajet t
        JOIN utilisateur u ON t.id_utilisateur_auteur = u.id_utilisateur
        JOIN agence a_depart ON t.id_agence_depart = a_depart.id_agence
        JOIN agence a_arrivee ON t.id_agence_arrivee = a_arrivee.id_agence
        ORDER BY t.date_heure_depart ASC
    ";
    $stmt = $pdo->query($query_trajets);
    $trajets = $stmt->fetchAll();
} catch (Exception $e) {
    $erreur_trajets = $e->getMessage();
}

include_once 'includes/header.php';
?>
<div class="main-container">
    <h2>Trajets proposés</h2>
    
    <?php if (isset($erreur_trajets)): ?>
        <p class="status-error">Erreur lors de la récupération des trajets : <?php echo $erreur_trajets; ?></p>
    <?php else: ?>
        <table class="trajets-table">
            <thead>
                <tr>
                    <th>Conducteur</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date & Heure</th>
                    <th class="text-center">Places restantes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($trajets) > 0): ?>
                    <?php foreach ($trajets as $trajet): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($trajet['conducteur_prenom'] . ' ' . $trajet['conducteur_nom']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($trajet['ville_depart']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($trajet['ville_arrivee']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($trajet['date_heure_depart']))); ?>
                            </td>
                            <td class="text-center">
                                <?php echo htmlspecialchars($trajet['places_disponibles']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">
                            Aucun trajet n'est disponible pour le moment.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php 
include_once 'includes/footer.php';
?>