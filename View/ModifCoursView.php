<?php
session_start();
include('../header.php');
require_once('../Controller/user.php');

if ($_SESSION["admin"] != 1) {
    header("Location: ./Bienvenue.php");
    exit;
}

if (!isset($_SESSION['user_email'])) {
    header("Location: ../View/login_view.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "bddcrud");

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$message = isset($_GET['message']) ? $_GET['message'] : '';

$sql = "SELECT * FROM Cours ORDER BY date DESC;";
$result = $conn->query($sql);
$user = new User($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Cours</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .cours-passe {
            display: none;
        }

        .inscription-btn,
        .desinscription-btn {
            cursor: pointer;
            color: #fff;
            background-color: #007bff;
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
        <h2>Liste des Cours pouvant être modifié</h2>
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
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $isInscriptionPossible = strtotime($row['date']) >= strtotime('today');
                        $isUserInscrit = $user->checkIfUserIsInscrit($_SESSION['user_id'], $row['id_cours']);

                        echo "<tr" . (strtotime($row['date']) < strtotime('today') ? ' class="cours-passe"' : '') . ">";
                        echo "<td>" . $row["id_cours"] . "</td>";
                        echo "<td>" . $row["date"] . "</td>";
                        echo "<td>" . $row["sujet"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        echo "<td>" . $row["intervenant"] . "</td>";
                        echo "<td>" . $row["duree_cours"] . "</td>";
                        echo "<td>" . $row["heure_cours"] . "</td>";
                        echo "<td>";
                        echo "<a href='edit_cours.php?id={$row["id_cours"]}'>Modifier</a>";
                        echo "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Aucun cours trouvé.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    $conn->close();
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
        });
    </script>

</body>

</html>

<?php
include('../footer.php');
?>
