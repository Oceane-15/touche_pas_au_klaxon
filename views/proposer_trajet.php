<?php include_once 'includes/header.php'; 
/** @var array $agences */?>

<div class="form-container">
    <h2>Proposer un nouveau trajet</h2>

    <?php if (!empty($error_message)): ?>
        <p class="status-error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="/trajets" method="POST" class="trajet-form">
        <div class="form-group">
            <label for="id_agence_depart">Ville de départ :</label>
            <select name="id_agence_depart" id="id_agence_depart" required>
                <option value="">Choisir une ville</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?php echo $agence['id_agence']; ?>"><?php echo htmlspecialchars($agence['nom_ville']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_agence_arrivee">Ville d'arrivée :</label>
            <select name="id_agence_arrivee" id="id_agence_arrivee" required>
                <option value="">Choisir une ville</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?php echo $agence['id_agence']; ?>"><?php echo htmlspecialchars($agence['nom_ville']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="date_heure_depart">Date et heure de départ :</label>
            <input type="datetime-local" id="date_heure_depart" name="date_heure_depart" required>
        </div>

        <div class="form-group">
            <label for="date_heure_arrivee">Date et heure d'arrivée :</label>
            <input type="datetime-local" id="date_heure_arrivee" name="date_heure_arrivee" required>
        </div>

        <div class="form-group">
            <label for="places_totales">Nombre de places proposées :</label>
            <input type="number" id="places_totales" name="places_totales" min="1" max="8" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-submit-trajet">Publier le trajet</button>
            <a href="/" class="btn btn-cancel-trajet">Annuler</a>
        </div>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>