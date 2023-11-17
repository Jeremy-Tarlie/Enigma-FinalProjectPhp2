<?php
session_start();

class AjoutCoursView
{
    private $isAdmin;

    public function __construct()
    {
        $this->isAdmin = isset($_SESSION["admin"]) && $_SESSION["admin"] == 1;

        if (!$this->isAdmin) {
            header("Location: ./Bienvenue.php");
            exit;
        }
    }

    public function displayHeader()
    {
        include('../header.php');
    }

    public function displaySuccessMessage()
    {
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div class="alert alert-success" role="alert">Cours ajouté avec succès !</div>';
        }
    }

    public function displayForm()
    {
        ?>
        <div class="container">
            <h2>Ajouter un Cours</h2>
            <form action="../ModelTraitementAjoutCours.php" method="post" id="ajoutCoursForm" onsubmit="return confirmerAjoutCours();">
                <div class="form-group">
                    <label for="date">Date :</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <input type="text" name="sujet" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label for="intervenant">Intervenant :</label>
                    <input type="text" name="intervenant" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="duree_cours">Durée du Cours (HH:MM:SS) :</label>
                    <input type="text" name="duree_cours" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="heure_cours">Heure du Cours (HH:MM:SS) :</label>
                    <input type="text" name="heure_cours" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Ajouter le cours</button>
            </form>
        </div>
        <?php
    }

    public function displayFooter()
    {
        include('../footer.php');
    }
}

$ajoutCoursView = new AjoutCoursView();
$ajoutCoursView->displayHeader();
$ajoutCoursView->displaySuccessMessage();
$ajoutCoursView->displayForm();
$ajoutCoursView->displayFooter();
?>
