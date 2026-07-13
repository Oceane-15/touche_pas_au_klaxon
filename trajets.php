<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error_message = "";
$success_message = "";

try {
    $stmt = $pdo->query("SELECT * FROM agence ORDER BY nom_ville ASC");
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
            
            $stmt_insert = $pdo->prepare($sql);
            $stmt_insert->execute([
                ':date_depart'       => $date_heure_depart,
                ':date_arrivee'      => $date_heure_arrivee,
                ':places_totales'    => $places_totales,
                ':places_disponibles'=> $places_totales,
                ':id_auteur'         => $_SESSION['user_id'],
                ':id_dep'            => $id_agence_depart,
                ':id_arr'            => $id_agence_arrivee
            ]);

            header('Location: index.php?success=1');
            exit;

        } catch (Exception $e) {
            $error_message = "Impossible d'ajouter le trajet : " . $e->getMessage();
        }
    }
}

include_once 'includes/header.php';
?>

<div class="form-container">
    <h2>Proposer un nouveau trajet</h2>

    <?php if (!empty($error_message)): ?>
        <p class="status-error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="trajets.php" method="POST" class="trajet-form">
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
    <a href="index.php" class="btn btn-cancel-trajet">Annuler</a>
</div>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>