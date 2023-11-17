<?php
session_start();
include('../header.php');
include('../Controller/User.php');

class EditCours
{
    private $conn;
    private $cours;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getCoursDetails($id)
    {
        $query = "SELECT * FROM cours WHERE id_cours=$id";
        $result = $this->conn->query($query);

        if ($result->num_rows == 1) {
            $this->cours = $result->fetch_assoc();
        } else {
            header("Location: ../View/ModifCoursView.php?message=Cours non trouvé.");
            exit();
        }
    }

    public function displayCoursForm()
    {
        ?>
        <div class="container mt-5">
            <h2>Modifier le Cours</h2>
            <form action="../ModelTraitementModificationCours.php" method="post" id="modificationCoursForm"
                  onsubmit="return confirmerModificationCours();">
                <input type="hidden" name="id_cours" value="<?php echo $this->cours['id_cours']; ?>">

                <div class="form-group">
                    <label for="date">Date :</label>
                    <input type="date" name="date" class="form-control" value="<?php echo $this->cours['date']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <input type="text" name="sujet" class="form-control" value="<?php echo $this->cours['sujet']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea name="description" class="form-control"
                              required><?php echo $this->cours['description']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="intervenant">Intervenant :</label>
                    <input type="text" name="intervenant" class="form-control"
                           value="<?php echo $this->cours['intervenant']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="duree_cours">Durée du Cours (HH:MM:SS) :</label>
                    <input type="text" name="duree_cours" class="form-control"
                           value="<?php echo $this->cours['duree_cours']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="heure_cours">Heure du Cours (HH:MM:SS) :</label>
                    <input type="text" name="heure_cours" class="form-control"
                           value="<?php echo $this->cours['heure_cours']; ?>" required>
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
    }
}

$conn = new mysqli("localhost", "root", "", "bddcrud");
$editCours = new EditCours($conn);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id_cours = $_GET["id"];
    $editCours->getCoursDetails($id_cours);
    $editCours->displayCoursForm();
} else {
    header("Location: ../View/ModifCoursView.php?message=ID du cours non fourni.");
    exit();
}

include('../footer.php');
?>
