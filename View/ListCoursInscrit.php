<?php
session_start();
include('../header.php');
require_once('../Controller/User.php');

class ListCoursInscrit
{
    private $conn;
    private $user;

    public function __construct($conn, $user)
    {
        $this->conn = $conn;
        $this->user = $user;
    }

    public function displayCoursList()
    {
        $message = isset($_GET['message']) ? $_GET['message'] : '';

        $inscritCours = $this->user->getInscritCours($_SESSION['user_id']);
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Liste des Cours Inscrits</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                .cours-passe {
                    display: none;
                }

                .desinscription-btn {
                    cursor: pointer;
                    color: #fff;
                    background-color: #dc3545;
                    border: none;
                    padding: 5px 10px;
                    border-radius: 4px;
                }

                .message {
                    margin-top: 10px;
                    padding: 10px;
                    border: 1px solid #ddd;
                }

                .success {
                    color: green;
                }

                .error {
                    color: red;
                }
            </style>
        </head>

        <body>

            <div class="container mt-5">
                <h2>Liste des Cours Inscrits</h2>
                <?php if (!empty($message)) : ?>
                    <div class="message <?php echo strpos($message, 'Erreur') !== false ? 'error' : 'success'; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="container mt-3">
                    <label for="toggleButton">Afficher les anciens cours</label>
                    <input type="checkbox" id="toggleButton">
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Sujet</th>
                            <th>Description</th>
                            <th>Intervenant</th>
                            <th>Durée du Cours</th>
                            <th>Heure du Cours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($inscritCours as $row) {
                            $isDesinscriptionPossible = strtotime($row['date']) >= strtotime('today');

                            echo "<tr" . (strtotime($row['date']) < strtotime('today') ? ' class="cours-passe"' : '') . ">";
                            echo "<td>" . $row["id_cours"] . "</td>";
                            echo "<td>" . $row["date"] . "</td>";
                            echo "<td>" . $row["sujet"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["intervenant"] . "</td>";
                            echo "<td>" . $row["duree_cours"] . "</td>";
                            echo "<td>" . $row["heure_cours"] . "</td>";

                            echo "<td>";
                            if ($isDesinscriptionPossible) {
                                echo "<button class='desinscription-btn' data-cours-id='{$row["id_cours"]}'>Se désinscrire</button>";
                            }
                            echo "</td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php
            $this->conn->close();
            ?>

            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script>
                $(document).ready(function () {
                    $("#toggleButton").change(function () {
                        if ($(this).is(":checked")) {
                            $(".cours-passe").show();
                        } else {
                            $(".cours-passe").hide();
                        }
                    });

                    $(".desinscription-btn").click(function () {
                        var coursId = $(this).data("cours-id");
                        $.ajax({
                            type: "POST",
                            url: "../Model/DesinscriptionCours.php",
                            data: { id_cours: coursId },
                            success: function (response) {
                                alert(response);
                                location.reload();
                            },
                            error: function () {
                                alert("Une erreur s'est produite lors de la désinscription.");
                            }
                        });
                    });
                });
            </script>

        </body>

        </html>

        <?php
        include('../footer.php');
    }
}
$conn = new mysqli("localhost", "root", "", "bddcrud");
$user = new User($conn);
$listCoursInscrit = new ListCoursInscrit($conn, $user);
$listCoursInscrit->displayCoursList();
?>
