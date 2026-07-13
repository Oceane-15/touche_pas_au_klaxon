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
                                    <a href="#" title="Voir" data-bs-toggle="modal" data-bs-target="#modalTrajet<?php echo $trajet['id_trajet']; ?>">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($trajet['id_utilisateur_auteur'] == $_SESSION['user_id']): ?>
                                        <a href="modifier_trajet.php?id=<?php echo $trajet['id_trajet']; ?>" title="Modifier"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="supprimer_trajet.php?id=<?php echo $trajet['id_trajet']; ?>" title="Supprimer" onclick="return confirm('Supprimer ce trajet ?');"><i class="fa-solid fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>

                        <?php if ($is_connected): ?>
                            <div class="modal fade" id="modalTrajet<?php echo $trajet['id_trajet']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $trajet['id_trajet']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="modalLabel<?php echo $trajet['id_trajet']; ?>">Détails du trajet</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-dark">
                                            <p><strong>Auteur :</strong> <?php echo htmlspecialchars($trajet['auteur_prenom'] . ' ' . $trajet['auteur_nom']); ?></p>
                                            <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($trajet['auteur_telephone']); ?></p>
                                            <p><strong>Email :</strong> <?php echo htmlspecialchars($trajet['auteur_email']); ?></p>
                                            <hr>
                                            <p><strong>Nombre total de places :</strong> <?php echo htmlspecialchars($trajet['places_totales']); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

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