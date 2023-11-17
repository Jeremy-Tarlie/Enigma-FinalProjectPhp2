<?php
include('./header.php');
session_start();

class DesinscriptionCours
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "bddcrud");

        if ($this->conn->connect_error) {
            die("Erreur N°1-20 Contactez un administrateur");
        }
    }

    public function processRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_cours"])) {
            $this->handlePostRequest();
        } else {
            echo "Une erreur s'est produite.";
        }
    }

    private function handlePostRequest()
    {
        if (!isset($_SESSION['user_email'])) {
            die("Vous n'êtes pas connecté.");
        }

        if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
            die("L'ID de l'utilisateur n'est pas valide.");
        }

        $userId = $_SESSION['user_id'];
        $coursId = $_POST["id_cours"];

        $deleteSql = "DELETE FROM inscription_cours WHERE id_utilisateur = ? AND id_cours = ?";
        $stmt = $this->conn->prepare($deleteSql);

        if (!$stmt) {
            die("Erreur N°1-21 Contactez un administrateur");
        }

        $stmt->bind_param("ii", $userId, $coursId);

        if ($stmt->execute()) {
            $updateStatutSql = "UPDATE utilisateurs SET statut = 'desincrit' WHERE id = ?";
            $stmtUpdateStatut = $this->conn->prepare($updateStatutSql);

            if ($stmtUpdateStatut) {
                $stmtUpdateStatut->bind_param("i", $userId);
                $stmtUpdateStatut->execute();
                $stmtUpdateStatut->close();
            }

            echo "Désinscription réussie!";
        } else {
            die("Erreur lors de la désinscription, Contactez un administrateur");
        }

        $stmt->close();
        $this->conn->close();
        exit();
    }
}

$desinscriptionCours = new DesinscriptionCours();
$desinscriptionCours->processRequest();
?>
