<?php
session_start();

require_once('../Controller/User.php');
require_once('../Database/database.php');

class ProfileModificationView
{
    private $databas;
    private $user;

    public function __construct($database, $user)
    {
        $this->database = $database;
        $this->user = $user;
    }

    public function renderProfileModification()
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
                <title>Modifier Profil</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                    }

                    .navbar {
                        background-color: #333;
                        color: #fff;
                        text-align: center;
                        padding: 15px;
                        width: 100%;
                    }

                    .profile-container {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        width: 400px;
                        margin: 50px 0;
                        text-align: center;
                    }

                    .form-group {
                        margin-bottom: 15px;
                    }

                    .form-group label {
                        display: block;
                        margin-bottom: 5px;
                    }

                    .form-group input {
                        width: 100%;
                        padding: 8px;
                        box-sizing: border-box;
                        border: 1px solid #ccc;
                        border-radius: 4px;
                    }

                    .form-group button {
                        background-color: #4caf50;
                        color: #fff;
                        padding: 10px;
                        border: none;
                        border-radius: 4px;
                        cursor: pointer;
                    }
                </style>
            </head>

            <body>
                <div class="profile-container">
                    <h1>Modifier votre profil</h1>

                    <form action="../Model/ModifProfile.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="email" value="<?php echo $user['email']; ?>">

                        <div class="form-group">
                            <label for="nom">Nom :</label>
                            <input type="text" name="nom" value="<?php echo $user['last_name']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="prenom">Prénom :</label>
                            <input type="text" name="prenom" value="<?php echo $user['first_name']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="image">Image de profil :</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <button type="submit">Valider</button>
                        </div>
                    </form>
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

// Utilisation de la classe ProfileModificationView
$database = new Database("localhost", "root", "", "bddcrud");
$user = new User($database);
$profileModificationView = new ProfileModificationView($database, $user);

$profileModificationView->renderProfileModification();
?>