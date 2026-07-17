<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';
/** @var PDO $pdo */

if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit();
}

$id_trajet = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;

try {
    $stmt = $pdo->prepare("SELECT * FROM trajet WHERE id_trajet = ?");
    $stmt->execute([$id_trajet]);
    $trajet = $stmt->fetch();

    if (!$trajet) {
        header('Location: /');
        exit();
    }

    if (!$is_admin && $trajet['id_utilisateur_auteur'] != $user_id) {
        header('Location: /');
        exit();
    }

    $agences = $pdo->query("SELECT * FROM agence ORDER BY nom_ville ASC")->fetchAll();

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

$erreur = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_agence_depart = intval($_POST['id_agence_depart']);
    $id_agence_arrivee = intval($_POST['id_agence_arrivee']);
    $date_heure_depart = $_POST['date_heure_depart'];
    $date_heure_arrivee = $_POST['date_heure_arrivee'];
    $places_totales = intval($_POST['places_totales']);
    $places_disponibles = intval($_POST['places_disponibles']);

    if ($id_agence_depart === $id_agence_arrivee) {
        $erreur = "La ville de départ et d'arrivée ne peuvent pas être identiques.";
    } elseif ($places_disponibles > $places_totales) {
        $erreur = "Le nombre de places disponibles ne peut pas dépasser le nombre total de places.";
    } else {
        try {
            $query_update = "
                UPDATE trajet 
                SET id_agence_depart = ?, id_agence_arrivee = ?, date_heure_depart = ?, date_heure_arrivee = ?, places_totales = ?, places_disponibles = ?
                WHERE id_trajet = ?
            ";
            $stmt_update = $pdo->prepare($query_update);
            $stmt_update->execute([
                $id_agence_depart,
                $id_agence_arrivee,
                $date_heure_depart,
                $date_heure_arrivee,
                $places_totales,
                $places_disponibles,
                $id_trajet
            ]);

            header('Location: /');
            exit();
        } catch (Exception $e) {
            $erreur = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}

include_once 'includes/header.php';
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