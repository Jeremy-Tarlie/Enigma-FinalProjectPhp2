<?php
session_start();

class TraitementAjoutCours
{
    private $db;

    public function __construct()
    {
        require_once('../Database/database.php');
        $this->db = new Database("localhost", "root", "", "bddcrud");
    }

    public function processRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->handlePostRequest();
        } else {
            header("Location: ../View/AjoutCoursView.php");
            exit();
        }
    }

    private function handlePostRequest()
    {
        $date = $_POST["date"];
        $sujet = $_POST["sujet"];
        $description = $_POST["description"];
        $intervenant = $_POST["intervenant"];
        $duree_cours = $_POST["duree_cours"];
        $heure_cours = $_POST["heure_cours"];

        $query = "INSERT INTO cours (date, sujet, description, intervenant, duree_cours, heure_cours) 
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("ssssss", $date, $sujet, $description, $intervenant, $duree_cours, $heure_cours);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cours ajouté avec succès.";
            header("Location: ../View/Bienvenue.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'ajout du cours. Veuillez réessayer.";
            header("Location: ../View/AjoutCoursView.php");
            exit();
        }
    }
}

// Utilisation
$traitementAjoutCours = new TraitementAjoutCours();
$traitementAjoutCours->processRequest();
?>
