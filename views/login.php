<?php 
include_once 'includes/header.php'; 

/** @var string $error_message */
?>

<div class="login-container">
    <h2>Connexion</h2>

    <?php if (!empty($error_message)): ?>
        <div class="flash-message flash-error">
         <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST" class="login-form">
        <div class="form-group">
            <label for="email">Adresse email :</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-black btn-submit">
            Se connecter
        </button>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>