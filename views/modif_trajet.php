<?php 
include_once 'includes/header.php'; 

/** @var array $trajet */
/** @var array $agences */
/** @var int $id_trajet */
/** @var bool $is_admin */
/** @var string|null $erreur */
?>

<div class="main-container">
    <div class="form-container">
        <h2>
            Modifier le trajet n°<?php echo $id_trajet; ?> 
            <?php if ($is_admin): ?>
                <span class="badge bg-danger admin-badge">Mode Admin</span>
            <?php endif; ?>
        </h2>

        <?php if ($erreur): ?>
            <p class="status-error"><?php echo $erreur; ?></p>
        <?php endif; ?>

        <form action="" method="POST" class="trajet-form">
            <div class="form-group">
                <label for="id_agence_depart">Agence de départ</label>
                <select name="id_agence_depart" id="id_agence_depart" required>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?php echo $agence['id_agence']; ?>" <?php echo ($trajet['id_agence_depart'] == $agence['id_agence']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($agence['nom_ville']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_agence_arrivee">Agence d'arrivée</label>
                <select name="id_agence_arrivee" id="id_agence_arrivee" required>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?php echo $agence['id_agence']; ?>" <?php echo ($trajet['id_agence_arrivee'] == $agence['id_agence']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($agence['nom_ville']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date_heure_depart">Date et heure de départ</label>
                <input type="datetime-local" name="date_heure_depart" id="date_heure_depart" value="<?php echo date('Y-m-d\TH:i', strtotime($trajet['date_heure_depart'])); ?>" required>
            </div>

            <div class="form-group">
                <label for="date_heure_arrivee">Date et heure d'arrivée</label>
                <input type="datetime-local" name="date_heure_arrivee" id="date_heure_arrivee" value="<?php echo date('Y-m-d\TH:i', strtotime($trajet['date_heure_arrivee'])); ?>" required>
            </div>

            <div class="form-group">
                <label for="places_totales">Nombre total de places</label>
                <input type="number" name="places_totales" id="places_totales" min="1" value="<?php echo $trajet['places_totales']; ?>" required>
            </div>

            <div class="form-group">
                <label for="places_disponibles">Places encore disponibles</label>
                <input type="number" name="places_disponibles" id="places_disponibles" min="0" value="<?php echo $trajet['places_disponibles']; ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit-trajet">Enregistrer les modifications</button>
                <a href="/" class="btn-cancel-trajet">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>