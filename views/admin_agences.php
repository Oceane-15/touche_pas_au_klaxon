<?php include_once 'includes/header.php'; 
/** @var string $action */
/** @var array $agences */
/** @var array|null $agence_to_edit */
/** @var string $message */
/** @var string $error */
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
                    <form action="/admin/agences" method="POST" class="form-agence-admin">
                        <input type="hidden" name="id_agence" value="<?php echo $agence_to_edit['id_agence']; ?>">
                        <div class="form-group row-gap-sm">
                            <label for="nom_ville" class="fw-bold fs-6">Nom de la ville</label>
                            <input type="text" id="nom_ville" name="nom_ville" class="form-control" value="<?php echo htmlspecialchars($agence_to_edit['nom_ville']); ?>" required>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" name="edit_agence" class="btn btn-primary flex-grow-1">Enregistrer</button>
                            <a href="/admin/agences" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                <?php else: ?>
                    <h4 class="mb-3">Ajouter une agence</h4>
                    <form action="/admin/agences" method="POST" class="form-agence-admin">
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
                                                <a href="/admin/agences?action=edit&id=<?php echo $agence['id_agence']; ?>" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Modifier l'agence">
                                                    <i class="fa-solid fa-pen"></i> Modifier
                                                </a>
                                                <a href="/admin/agences?action=delete&id=<?php echo $agence['id_agence']; ?>" 
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

<?php require_once 'includes/footer.php'; ?>