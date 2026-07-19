<?php

class AuthController {
    private PDO $pdo;

    public function __construct(PDO $pdo, bool $check_logged = true) {
        if (session_status() === PHP_SESSION_NONE) { 
            session_start();
        }
        $this->pdo = $pdo;

        if ($check_logged && isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
    }

    public function login() {
        $error_message = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (!empty($email) && !empty($password)) {
                $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                $password_clean = trim($password); 

                if ($user && password_verify($password_clean, $user['mot_de_passe'])) { 
                    $_SESSION['user_id'] = $user['id_utilisateur'];
                    $_SESSION['user_firstname'] = $user['prenom'];
                    $_SESSION['user_lastname'] = $user['nom'];
                    $_SESSION['user_role'] = $user['est_admin'];

                    header('Location: /');
                    exit;
                } else {
                    $error_message = "Email ou mot de passe incorrect.";
                }
            } else {
                $error_message = "Veuillez remplir tous les champs.";
            }
        }

        require_once __DIR__ . '/../views/login.php';
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();

        header('Location: /');
        exit;
    }
}