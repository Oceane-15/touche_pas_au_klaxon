<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';
/** @var PDO $pdo */

$is_admin = isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
if (!$is_admin) {
    header('Location: /');
    exit();
}

$message = '';
$error = '';

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id_to_delete = intval($_GET['id']);
    
    if ($id_to_delete === (int)$_SESSION['user_id']) {
        $error = "Vous ne pouvez pas supprimer votre propre compte administrateur.";
    } else {
        try {
            $stmt_trajets = $pdo->prepare("DELETE FROM trajet WHERE id_utilisateur_auteur = ?");
            $stmt_trajets->execute([$id_to_delete]);
            $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
            $stmt->execute([$id_to_delete]);
            $message = "L'utilisateur a été supprimé avec succès.";
        } catch (Exception $e) {
            $error = "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
        }
    }
}

try {
    $stmt = $pdo->query("SELECT id_utilisateur, nom, prenom, email, telephone, est_admin FROM utilisateur ORDER BY nom ASC");
    $utilisateurs = $stmt->fetchAll();
} catch (Exception $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

require_once 'includes/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des Utilisateurs</h2>
        <span class="badge bg-secondary"><?php echo count($utilisateurs); ?> collaborateur(s)</span>
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

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nom / Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($utilisateurs)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($utilisateurs as $user): ?>
                            <tr>
                                <td class="fw-bold">
                                    <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['telephone'] ?? 'Non renseigné'); ?></td>
                                <td>
                                    <?php if ((int)$user['est_admin'] === 1): ?>
                                        <span class="badge bg-danger">Administrateur</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Collaborateur</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['id_utilisateur'] !== $_SESSION['user_id']): ?>
                                        <a href="/admin/utilisateurs?action=delete&id=<?php echo $user['id_utilisateur']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"
                                           title="Supprimer l'utilisateur">
                                            <i class="fa-solid fa-trash"></i> Supprimer
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small"><em>Votre compte</em></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>