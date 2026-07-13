<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';

$is_connected = isset($_SESSION['user_id']);

try {
    $query_trajets = "
        SELECT 
            t.id_trajet,
            t.date_heure_depart,
            t.date_heure_arrivee,
            t.places_disponibles,
            t.id_utilisateur_auteur,
            a_depart.nom_ville AS ville_depart,
            a_arrivee.nom_ville AS ville_arrivee
        FROM trajet t
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

    <?php if ($is_connected): ?>
        <h2>Trajets proposés</h2>
    <?php else: ?>
        <h2 class="visitor-message" style="font-size: 1.5rem; font-weight: normal; margin-bottom: 20px;">Pour obtenir plus d'informations sur un trajet, veuillez vous connecter</h2>
    <?php endif; ?>
    
    <?php if (isset($erreur_trajets)): ?>
        <p class="status-error">Erreur : <?php echo $erreur_trajets; ?></p>
    <?php else: ?>
        <table class="trajets-table">
            <thead>
                <tr>
                    <th>Départ</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Places</th>
                    <?php if ($is_connected): ?>
                        <th style="text-align: center;">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (count($trajets) > 0): ?>
                    <?php foreach ($trajets as $trajet): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trajet['ville_depart']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($trajet['date_heure_depart'])); ?></td>
                            <td><?php echo date('H:i', strtotime($trajet['date_heure_depart'])); ?></td>
                            
                            <td><?php echo htmlspecialchars($trajet['ville_arrivee']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($trajet['date_heure_arrivee'])); ?></td>
                            <td><?php echo date('H:i', strtotime($trajet['date_heure_arrivee'])); ?></td>
                            
                            <td><?php echo htmlspecialchars($trajet['places_disponibles']); ?></td>
                            
                            <?php if ($is_connected): ?>
                                <td class="actions-cell">
                                    <a href="voir_trajet.php?id=<?php echo $trajet['id_trajet']; ?>" title="Voir"><i class="fa-solid fa-eye"></i></a>
                                    
                                    <?php if ($trajet['id_utilisateur_auteur'] == $_SESSION['user_id']): ?>
                                        <a href="modifier_trajet.php?id=<?php echo $trajet['id_trajet']; ?>" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="supprimer_trajet.php?id=<?php echo $trajet['id_trajet']; ?>" title="Supprimer" onclick="return confirm('Supprimer ce trajet ?');"><i class="fa-solid fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="no-data">
                        <td colspan="<?php echo $is_connected ? '8' : '7'; ?>">Aucun trajet proposé pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include_once 'includes/footer.php'; ?>