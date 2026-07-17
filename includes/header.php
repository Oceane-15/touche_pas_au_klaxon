<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_connected = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
$user_name = $is_connected ? $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'] : '';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touche pas au klaxon</title>
    <link rel="stylesheet" href="/assets/css/bootstrap-custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="main-wrapper">
        <header class="custom-header">
            <a href="/" class="brand" style="text-decoration: none; color: inherit;">Touche pas au klaxon</a>

            <div class="nav-controls">
                <?php if (!$is_connected): ?>
                    <a href="/login" class="btn btn-black">Connexion</a>
                <?php else: ?>
                    <?php if ($is_admin): ?>
                        <a href="/admin/utilisateurs" class="btn btn-black">Utilisateurs</a>
                        <a href="/admin/agences" class="btn btn-black">Agences</a>
                        <a href="/trajets" class="btn btn-black">Trajets</a>
                    <?php else: ?>
                        <a href="/trajets" class="btn btn-black">Créer un trajet</a>
                    <?php endif; ?>

                    <span class="user-greeting">Bonjour <?php echo htmlspecialchars($user_name); ?></span>
                    <a href="/logout" class="btn btn-black">Déconnexion</a>
                <?php endif; ?>
            </div>
        </header>
        <main class="content">