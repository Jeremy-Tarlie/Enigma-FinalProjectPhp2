<?php
session_start();

class Bienvenue
{
    private $userEmail;

    public function __construct()
    {
        if (!isset($_SESSION['user_email'])) {
            header("Location: ../index.php");
            exit();
        }

        $this->userEmail = $_SESSION['user_email'];
    }

    public function displayWelcomeMessage()
    {
        include('../header.php');
        ?>
        <h2>Bienvenue <?php echo $this->userEmail; ?></h2>
        <p>Ceci est la page de bienvenue.</p>
        <!-- <a href="logout.php">Se déconnecter</a> -->
        <?php
        include('../footer.php');
    }
}

// Utilisation
$Bienvenue = new Bienvenue();
$Bienvenue->displayWelcomeMessage();
?>
