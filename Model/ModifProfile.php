<?php
session_start();

class ModifProfile
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function updateProfile($email, $nom, $prenom)
    {
        $conn = $this->database->getConnection();
        $stmt  = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?;");
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user =  $stmt->get_result()->fetch_assoc();

        $image = "";
        
        // Vérifier si le fichier a été correctement téléchargé
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
            // Emplacement où vous souhaitez sauvegarder le fichier téléchargé
            $repertoireDuFichier = dirname(__FILE__);
            $target_dir = $repertoireDuFichier."/../Public/img/";
            $target_file = $target_dir . "image#". $user['id']  . ".png";
            
            $image=  "image#". $user['id']  . ".png";
            // Déplacer le fichier téléchargé vers l'emplacement souhaité
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "L'image a été téléchargée avec succès.";
            } else {
                echo "Une erreur s'est produite lors du téléchargement de l'image.";
            }
        }


        $sql = "UPDATE utilisateurs SET last_name = ?, first_name = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nom, $prenom, $email);

        if ($stmt->execute()) {
            echo "Profil mis à jour avec succès. Redirection vers votre profil...";
            header("refresh:1;url=../View/ProfilView.php");
        } else {
            echo "Erreur lors de la mise à jour du profil : " . $stmt->error;
        }

        $stmt->close();
        $this->database->closeConnection();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../Database/database.php');

    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    if (empty($nom) || empty($prenom)) {
        echo "Veuillez remplir tous les champs.";
    } else {
        $database = new Database("localhost", "root", "", "bddcrud");
        $ModifProfile = new ModifProfile($database);

        $ModifProfile->updateProfile($email, $nom, $prenom);
    }
} else {
    echo "Méthode non autorisée.";
}
?>
