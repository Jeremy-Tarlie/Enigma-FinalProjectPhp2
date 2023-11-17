<?php
session_start();
include('../header.php');
include('../Controller/User.php');

if (!isset($_SESSION['user_email'])) {
    header("Location: ../View/LoginView.php");
    exit();
}

class CoursModifier
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getCoursById($id_cours)
    {
        $query = "SELECT * FROM cours WHERE id_cours=$id_cours";
        $result = $this->conn->query($query);

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_cours"])) {
    $id_cours = $_GET["id_cours"];

    $conn = new mysqli("localhost", "root", "", "bddcrud");

    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    $coursModifier = new CoursModifier($conn);
    $cours = $coursModifier->getCoursById($id_cours);

    if (!$cours) {
        header("Location: edit_cours.php?message=Cours non trouvé.");
        exit();
    }
} else {
    var_dump($_GET["id_cours"]);
    header("Location: edit_cours.php?message=ID du cours non fourni.");
    exit();
}
?>

<div class="container mt-5">
    <h2>Modifier le Cours</h2>
    <form action="../ModelTraitementModificationCours.php" method="post" id="modificationCoursForm" onsubmit="return confirmerModificationCours();">
        <input type="hidden" name="id_cours" value="<?php echo $cours['id_cours']; ?>">

        <div class="form-group">
            <label for="date">Date :</label>
            <input type="date" name="date" class="form-control" value="<?php echo $cours['date']; ?>" required>
        </div>

        <div class="form-group">
            <label for="sujet">Sujet :</label>
            <input type="text" name="sujet" class="form-control" value="<?php echo $cours['sujet']; ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea name="description" class="form-control" required><?php echo $cours['description']; ?></textarea>
        </div>

        <div class="form-group">
            <label for="intervenant">Intervenant :</label>
            <input type="text" name="intervenant" class="form-control" value="<?php echo $cours['intervenant']; ?>" required>
        </div>

        <div class="form-group">
            <label for="duree_cours">Durée du Cours (HH:MM:SS) :</label>
            <input type="text" name="duree_cours" class="form-control" value="<?php echo $cours['duree_cours']; ?>" required>
        </div>

        <div class="form-group">
            <label for="heure_cours">Heure du Cours (HH:MM:SS) :</label>
            <input type="text" name="heure_cours" class="form-control" value="<?php echo $cours['heure_cours']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Modifier le cours</button>
    </form>
</div>

<script>
    function confirmerModificationCours() {
        var confirmation = confirm("Voulez-vous vraiment modifier ce cours?");
        return confirmation;
    }
</script>

<?php
include('../footer.php');
?>
