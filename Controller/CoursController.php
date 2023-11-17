<?php
session_start();
include('../header.php');
require_once('../Controller/User.php');

class CoursController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function afficherListeCours()
    {
        if (!isset($_SESSION['user_email'])) {
            header("Location: ../View/LoginView.php");
            exit();
        }

        $message = isset($_GET['message']) ? $_GET['message'] : '';

        $result = $this->db->query("SELECT * FROM Cours ORDER BY date DESC;");
        $user = new User($this->db);

        include('../View/ListCoursView.php');
    }
}

$conn = new mysqli("localhost", "root", "", "bddcrud");

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$coursController = new CoursController($conn);
$coursController->afficherListeCours();

$conn->close();
include('../footer.php');
?>
