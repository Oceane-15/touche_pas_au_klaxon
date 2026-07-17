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

    $stmt_delete = $pdo->prepare("DELETE FROM trajet WHERE id_trajet = ?");
    $stmt_delete->execute([$id_trajet]);

    header('Location: /');
    exit();

} catch (Exception $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>