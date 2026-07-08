<?php
require_once 'includes/db.php';
include_once 'includes/header.php';
?>

<div class="welcome-box">
    <h1>Bienvenue sur Touche pas au klaxon !</h1>
    <p>La plateforme de covoiturage réservée à nos employés !</p>

    <?php
   try {
        $query = $pdo->query("SELECT COUNT(*) as total FROM utilisateur");
        $result = $query->fetch();
        echo "<p class='status-success'> Connexion BDD réussie ! Nombre d'employés enregistrés : " . $result['total'] . "</p>";
    } catch (Exception $e) {
        echo "<p class='status-error'> Erreur BDD : " . $e->getMessage() . "</p>";
    }
    ?>
</div>

<?php 
include_once 'includes/footer.php';
?>