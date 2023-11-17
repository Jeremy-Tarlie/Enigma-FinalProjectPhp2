<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once('../Database/database.php');
require_once('../Controller/User.php');
require_once '../vendor/autoload.php'; 
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;

class PasswordResetFunc
{
    private $user;
    private $mailer;

    public function __construct($user, $mailer)
    {
        $this->user = $user;
        $this->mailer = $mailer;
    }

    public function resetPassword($email)
    {
        $userData = $this->user->getUserData($email);

        if ($userData) {
            $resetToken = bin2hex(random_bytes(32)); 

            if ($this->user->updateResetToken($email, $resetToken)) {
                $resetLink = "localhost/projet_WEB_PHP_YUG4/Controller/ResetPasswordConfirm.php?email=$email&token=" . urlencode($resetToken);

                try {
                    $this->mailer->isSMTP();
                    $this->mailer->Host = 'smtp.office365.com';
                    $this->mailer->SMTPAuth = true;
                    $this->mailer->Username = 'devsarealsohumansV2@outlook.fr'; 
                    $this->mailer->Password = 'Doranco!123456789'; 
                    $this->mailer->SMTPSecure = 'tls';
                    $this->mailer->Port = 587;

                    $this->mailer->setFrom('devsarealsohumansV2@outlook.fr', 'Sacha Thibault');
                    $this->mailer->addAddress($email);
                    $this->mailer->isHTML(true);
                    $this->mailer->Subject = 'Réinitialisation de mot de passe';
                    $this->mailer->Body = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : $resetLink";

                    $this->mailer->send();
                    $_SESSION['reset_status'] = "Un e-mail a été envoyé avec le lien de réinitialisation.";
                } catch (Exception $e) {
                    $_SESSION['reset_status'] = "Erreur lors de l'envoi de l'e-mail : " . $this->mailer->ErrorInfo;
                }

            } else {
                $_SESSION['reset_status'] = "Échec de la réinitialisation du mot de passe.";
            }
        } else {
            $_SESSION['reset_status'] = "L'adresse e-mail n'existe pas.";
        }

        header("Location: ResetPasswordView.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../Database/database.php');

    $email = $_POST["email"];
    $user = new User(new Database("localhost", "root", "", "bddcrud"));
    $mailer = new PHPMailer(true);

    $PasswordResetFunc = new PasswordResetFunc($user, $mailer);
    $PasswordResetFunc->resetPassword($email);
}

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
            font-family: Arial, sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .login-container h2 {
            text-align: center;
            color: #007bff;
        }

        .login-container p {
            color: <?php echo isset($_SESSION['reset_status']) ? ($_SESSION['reset_status'] === "Un e-mail a été envoyé avec le lien de réinitialisation." ? "green" : "red") : "inherit"; ?>;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
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
        <form action="ResetPasswordView.php" method="post">
            <div class="form-group">
                <label for="email">Adresse E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <button type="submit">Réinitialiser le Mot de Passe</button>
            </div>
        </form>
    </div>
</body>

</html>
