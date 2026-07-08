<?php

require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start();
}

$error_message = "";

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        $password_clean = trim($password); 

        if ($user && password_verify($password_clean, $user['mot_de_passe'])) { 
            
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['user_firstname'] = $user['prenom'];
            $_SESSION['user_lastname'] = $user['nom'];
            $_SESSION['user_role'] = $user['est_admin'];

            header('Location: index.php');
            exit;
        } else {
            $error_message = "Email ou mot de passe incorrect.";
        }
    } else {
        $error_message = "Veuillez remplir tous les champs.";
    }
}

include_once 'includes/header.php';
?>

<div class="login-container">
    <h2>Connexion</h2>

    <?php if (!empty($error_message)): ?>
        <div class="flash-message flash-error">
         <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="login-form">
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