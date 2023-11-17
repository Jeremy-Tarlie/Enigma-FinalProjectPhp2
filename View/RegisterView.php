<?php
class RegisterView
{
    public function renderHeader()
    {
        include('../header.php');
    }

    public function renderForm()
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
            <title>Inscription</title>
            <style>
                body {
                    background-color: #f8f9fa;
                }

                .register-container {
                    max-width: 400px;
                    margin: auto;
                    margin-top: 50px;
                    padding: 20px;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                .register-container h2 {
                    text-align: center;
                }

                .form-group {
                    margin-bottom: 20px;
                }

                input[type="text"],
                input[type="email"],
                input[type="password"] {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 10px;
                    box-sizing: border-box;
                }

                input[type="submit"] {
                    width: 100%;
                    padding: 10px;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    cursor: pointer;
                }

                input[type="submit"]:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>

        <body>

            <div class="register-container">
                <h2>Inscription</h2>
                <form action="../Modele/Register.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="last_name">Nom :</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="first_name">Prénom :</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="date_de_naissance">Date de naissance :</label>
                        <input type="date" name="date_de_naissance" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="mot_de_passe">Mot de passe :</label>
                        <input type="password" name="mot_de_passe" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_mot_de_passe">Confirmer le mot de passe :</label>
                        <input type="password" name="confirm_mot_de_passe" class="form-control" required>
                    </div>

                   

                    <div class="form-group">
                        <input type="submit" value="S'inscrire" class="btn btn-primary">
                    </div>
                </form>
            </div>

            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        </body>

        </html>
        <?php
    }

    public function renderFooter()
    {
        include('../footer.php');
    }
}

$RegisterView = new RegisterView();
$RegisterView->renderHeader();
$RegisterView->renderForm();
$RegisterView->renderFooter();
?>
