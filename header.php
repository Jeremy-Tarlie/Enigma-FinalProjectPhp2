<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', dirname(__FILE__)));
// var_dump($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        nav {
            background-color: #333;
        }

        .lienNav {
            color: white;
            transition: text-decoration 0.3s;
        }

        .lienNav:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Logo</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (!isset($_SESSION['user_email'])) { ?>
                    <li class="nav-item">
                        <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/View/LoginView.php">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/View/RegisterView.php">Inscription</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/Controller/CoursController.php">Liste des cours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/View/ListCoursInscrit.php">Cours inscrit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/View/ProfilView.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/logout.php">Déconnexion</a>
                    </li>
                    
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) { ?>
                        <li class="nav-item">
                            <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/View/AjoutCoursView.php">Ajout Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link lienNav h5" href="<?php echo $base_url; ?>/View/ModifCoursView.php">Modifications Cours</a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
