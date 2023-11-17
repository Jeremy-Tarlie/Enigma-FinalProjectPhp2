<?php
include('../Database/database.php');

class Register
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registerUser($email, $first_name, $last_name, $mot_de_passe, $confirm_mot_de_passe)
    {
        if ($mot_de_passe === $confirm_mot_de_passe) {
            $mot_de_passe = password_hash($mot_de_passe, PASSWORD_BCRYPT);
            $conn = $this->database->getConnection();

            $sql = "INSERT INTO utilisateurs (email, mot_de_passe, first_name, last_name) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssss", $email, $mot_de_passe, $first_name, $last_name);

                if ($stmt->execute()) {
                    $this->database->closeConnection();
                    header("Location: ../View/LoginView.php");
                    exit();
                } else {
                    echo "Erreur lors de l'inscription : " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Erreur de préparation de la requête : " . $conn->error;
            }
        } else {
            echo "Les mots de passe ne correspondent pas.";
        }
    }
}

$database = new Database("localhost", "root", "", "bddcrud");
$Register = new Register($database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirm_mot_de_passe = $_POST['confirm_mot_de_passe'];

    $Register->registerUser($email, $first_name, $last_name, $mot_de_passe, $confirm_mot_de_passe);
}
?>
