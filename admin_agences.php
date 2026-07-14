<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';

$is_admin = isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
if (!$is_admin) {
    header('Location: index.php');
    exit();
}

$message = '';
$error = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$agence_to_edit = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_agence'])) {
        $nom_ville = trim($_POST['nom_ville']);
        if (!empty($nom_ville)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO agence (nom_ville) VALUES (?)");
                $stmt->execute([$nom_ville]);
                $message = "L'agence '" . htmlspecialchars($nom_ville) . "' a été ajoutée avec succès.";
            } catch (Exception $e) {
                $error = "Erreur lors de l'ajout : " . $e->getMessage();
            }
        } else {
            $error = "Le nom de la ville ne peut pas être vide.";
        }
    }
    
    if (isset($_POST['edit_agence'])) {
        $id_agence = intval($_POST['id_agence']);
        $nom_ville = trim($_POST['nom_ville']);
        if (!empty($nom_ville) && $id_agence > 0) {
            try {
                $stmt = $pdo->prepare("UPDATE agence SET nom_ville = ? WHERE id_agence = ?");
                $stmt->execute([$nom_ville, $id_agence]);
                $message = "L'agence a été modifiée avec succès.";
                $action = 'list'; 
            } catch (Exception $e) {
                $error = "Erreur lors de la modification : " . $e->getMessage();
            }
        } else {
            $error = "Le nom de la ville ne peut pas être vide.";
        }
    }
}

if (isset($_GET['action'])) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($_GET['action'] === 'delete' && $id > 0) {
        try {
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM trajet WHERE id_agence_depart = ? OR id_agence_arrivee = ?");
            $stmt_check->execute([$id, $id]);
            $linked_trajets = $stmt_check->fetchColumn();
            
            if ($linked_trajets > 0) {
                $error = "Impossible de supprimer cette agence car elle est associée à $linked_trajets trajet(s).";
            } else {
                $stmt = $pdo->prepare("DELETE FROM agence WHERE id_agence = ?");
                $stmt->execute([$id]);
                $message = "L'agence a été supprimée avec succès.";
            }
        } catch (Exception $e) {
            $error = "Erreur lors de la suppression : " . $e->getMessage();
        }
    }
    
    if ($_GET['action'] === 'edit' && $id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM agence WHERE id_agence = ?");
        $stmt->execute([$id]);
        $agence_to_edit = $stmt->fetch();
        if ($agence_to_edit) {
            $action = 'edit';
        }
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM agence ORDER BY nom_ville ASC");
    $agences = $stmt->fetchAll();
} catch (Exception $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des Agences (Villes)</h2>
        <span class="badge bg-secondary"><?php echo count($agences); ?> agence(s) installée(s)</span>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-light p-3">
                <?php if ($action === 'edit' && $agence_to_edit): ?>
                    <h4 class="mb-3">Modifier l'agence</h4>
                    <form action="admin_agences.php" method="POST" class="form-agence-admin">
                        <input type="hidden" name="id_agence" value="<?php echo $agence_to_edit['id_agence']; ?>">
                        <div class="form-group row-gap-sm">
                            <label for="nom_ville" class="fw-bold fs-6">Nom de la ville</label>
                            <input type="text" id="nom_ville" name="nom_ville" class="form-control" value="<?php echo htmlspecialchars($agence_to_edit['nom_ville']); ?>" required>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" name="edit_agence" class="btn btn-primary flex-grow-1">Enregistrer</button>
                            <a href="admin_agences.php" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                <?php else: ?>
                    <h4 class="mb-3">Ajouter une agence</h4>
                    <form action="admin_agences.php" method="POST" class="form-agence-admin">
                        <div class="form-group row-gap-sm">
                            <label for="nom_ville" class="fw-bold fs-6">Nom de la ville</label>
                            <input type="text" id="nom_ville" name="nom_ville" class="form-control" placeholder="Ex: Paris, Lyon..." required>
                        </div>
                        <button type="submit" name="add_agence" class="btn btn-success w-100 mt-3">Ajouter une agence</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom de l'agence</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($agences)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Aucune agence enregistrée pour le moment.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($agences as $agence): ?>
                                    <tr>
                                        <td><?php echo $agence['id_agence']; ?></td>
                                        <td class="fw-bold cell-ville"> 
                                            <?php echo htmlspecialchars($agence['nom_ville']); ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="admin_agences.php?action=edit&id=<?php echo $agence['id_agence']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Modifier l'agence">
                                                    <i class="fa-solid fa-pen"></i> Modifier
                                                </a>
                                                <a href="admin_agences.php?action=delete&id=<?php echo $agence['id_agence']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer l\'agence « <?php echo htmlspecialchars($agence['nom_ville']); ?> » ?');"
                                                   title="Supprimer l'agence">
                                                    <i class="fa-solid fa-trash"></i> Supprimer
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>