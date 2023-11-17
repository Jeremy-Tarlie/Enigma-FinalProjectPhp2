<?php
session_start();

require_once('../Controller/User.php');
require_once('../Database/database.php');

class ProfileView
{
    private $database;
    private $user;

    public function __construct($database, $user)
    {
        $this->database = $database;
        $this->user = $user;
    }

    public function renderProfile()
    {
        $email = $_SESSION['user_email'];
        $conn = $this->database->getConnection();

        $sql = "SELECT first_name, last_name, email FROM utilisateurs WHERE email = ?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Profil</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                    }

                    .profile-container {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        width: 400px;
                        margin: 50px auto;
                        text-align: center;
                    }

                    .info-group {
                        margin-bottom: 15px;
                    }

                    .info-group strong {
                        display: block;
                        margin-bottom: 5px;
                    }

                    .info-group p {
                        margin: 0;
                    }

                    .form-group {
                        margin-top: 20px;
                    }

                    .form-group a {
                        display: inline-block;
                        background-color: #4caf50;
                        color: #fff;
                        padding: 10px;
                        text-decoration: none;
                        border-radius: 4px;
                    }
                </style>
            </head>
            <body>
                <div class="profile-container">
                    <h1>Informations de l'utilisateur</h1>

                    <div class="info-group">
                        <strong>Email :</strong>
                        <p><?php echo $user['email']; ?></p>
                    </div>
                    <div class="info-group">
                        <strong>Nom :</strong>
                        <p><?php echo $user['last_name']; ?></p>
                    </div>
                    <div class="info-group">
                        <strong>Prénom :</strong>
                        <p><?php echo $user['first_name']; ?></p>
                    </div>

                    <div class="form-group">
                        <a href="ModifProfilView.php">Modifier son profil</a>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Identifiants incorrects.";
        }

        $this->database->closeConnection();
    }
}
$database = new Database("localhost", "root", "", "bddcrud");
$user = new User($database);
$profileView = new ProfileView($database, $user);

$profileView->renderProfile();
?>
