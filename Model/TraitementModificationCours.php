<?php
session_start();
include('../Controller/User.php');

class CoursModify
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function updateCours($id_cours, $date, $sujet, $description, $intervenant, $duree_cours, $heure_cours)
    {
        $query = $this->conn->prepare("UPDATE cours SET date=?, sujet=?, description=?, intervenant=?, duree_cours=?, heure_cours=? WHERE id_cours=?");
        $query->bind_param("ssssssi", $date, $sujet, $description, $intervenant, $duree_cours, $heure_cours, $id_cours);
        $result = $query->execute();

        return $result;
    }
}

if (!isset($_SESSION['user_email'])) {
    header("Location: ../View/LoginView.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "bddcrud");

    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    // Vérifiez l'existence des données postées
    $id_cours = isset($_POST['id_cours']) ? $_POST['id_cours'] : '';
    $date = isset($_POST["date"]) ? $_POST["date"] : '';
    $sujet = isset($_POST["sujet"]) ? $_POST["sujet"] : '';
    $description = isset($_POST["description"]) ? $_POST["description"] : '';
    $intervenant = isset($_POST["intervenant"]) ? $_POST["intervenant"] : '';
    $duree_cours = isset($_POST["duree_cours"]) ? $_POST["duree_cours"] : '';
    $heure_cours = isset($_POST["heure_cours"]) ? $_POST["heure_cours"] : '';

    $CoursModify = new CoursModify($conn);
    $result = $CoursModify->updateCours($id_cours, $date, $sujet, $description, $intervenant, $duree_cours, $heure_cours);

    if ($result) {
        header("Location: ../View/ModifCoursView.php?message=Mise à jour réussie.");
        exit();
    } else {
        header("Location: ../View/ModifCoursView.php?message=Erreur lors de la mise à jour. Veuillez réessayer. Erreur : " . $conn->error);
        exit();
    }
} else {
    header("Location: ../View/ModifCoursView.php");
    exit();
}
?>
