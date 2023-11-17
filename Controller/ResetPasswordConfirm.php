<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();

require_once('../Database/database.php');
require_once('../Controller/User.php');
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db = new Database("localhost", "root", "", "bddcrud");
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $email = isset($_GET["email"]) ? $_GET["email"] : "";
    $token = isset($_GET["token"]) ? $_GET["token"] : "";

    if ($user->validateResetToken($email, $token)) {
        ?>
        <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Réinitialisation du Mot de Passe</title>
                <style>
                    body {
                        background-color: #f8f9fa;
                    }

                    .login-container {
                        max-width: 400px;
                        margin: auto;
                        margin-top: 50px;
                        padding: 20px;
                        background-color: #fff;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }

                    .login-container h2 {
                        text-align: center;
                        color: #007bff;
                    }

                    .login-container p {
                        text-align: center;
                        color: <?php echo isset($_SESSION['reset_status']) && strpos($_SESSION['reset_status'], 'Erreur') !== false ? "red" : "green"; ?>;
                    }

                    .form-group {
                        margin-bottom: 20px;
                        display: flex;
                        flex-direction: column;
                    }

                    label {
                        margin-bottom: 5px;
                    }

                    input {
                        padding: 10px;
                        margin-bottom: 15px;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                    }

                    button {
                        padding: 10px;
                        background-color: #007bff;
                        color: #fff;
                        border: none;
                        cursor: pointer;
                    }

                    button:hover {
                        background-color: #0056b3;
                    }
                </style>
            </head>

            <body>
                <div class="login-container">
                    <h2>Réinitialisation du Mot de Passe</h2>
                    <?php
                    if (isset($_SESSION['reset_status'])) {
                        echo "<p>{$_SESSION['reset_status']}</p>";
                        unset($_SESSION['reset_status']);
                    }
                    ?>
                    <form action="ResetPasswordConfirm.php" method="post">
                        <input type="hidden" name="email" value="<?= $email ?>">
                        <input type="hidden" name="token" value="<?= $token ?>">
                        <div class="form-group">
                            <label for="new_password">Nouveau Mot de Passe:</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmer le Nouveau Mot de Passe:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="form-group">
                            <button type="submit">Réinitialiser le Mot de Passe</button>
                        </div>
                    </form>
                </div>
            </body>

        </html>

<?php
    } else {
        $_SESSION['reset_status'] = "Le lien de réinitialisation est invalide.";
        header("Location: ../View/ResetPasswordView.php");
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $token = isset($_POST["token"]) ? $_POST["token"] : "";
    $newPassword = isset($_POST["new_password"]) ? $_POST["new_password"] : "";
    $confirmPassword = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";

    if ($newPassword !== $confirmPassword) {
        $_SESSION['reset_status'] = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
        header("Location: ../Controller/ResetPasswordConfirm.php?email=$email&token=$token");
        exit();
    }

    if ($user->resetPassword($email, $newPassword)) {
        $_SESSION['login_status'] = "Mot de passe réinitialisé avec succès. Veuillez vous connecter avec votre nouveau mot de passe.";
        header("Location: ../View/LoginView.php");
        exit();
    } else {
        $_SESSION['reset_status'] = "Échec de la réinitialisation du mot de passe.";
        header("Location: ../View/ResetPasswordView.php");
        exit();
    }
}

$db->closeConnection();
?>
