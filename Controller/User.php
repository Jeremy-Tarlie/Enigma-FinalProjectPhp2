<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUserData($email)
    {
        $email = $this->db->getConnection()->real_escape_string($email);

        $stmt = $this->db->getConnection()->prepare("SELECT id, Admin, mot_de_passe FROM utilisateurs WHERE email=?");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                return $result->fetch_assoc();
            }
        }

        return false;
    }

    public function updateResetToken($email, $resetToken)
    {
        $email = $this->db->getConnection()->real_escape_string($email);
        $resetToken = $this->db->getConnection()->real_escape_string($resetToken);
        $query = "UPDATE utilisateurs SET reset_token='$resetToken' WHERE email='$email'";
        $result = $this->db->getConnection()->query($query);

        return $result;
    }

    public function resetPassword($email, $newPassword)
    {
        $email = $this->db->getConnection()->real_escape_string($email);
        $newPassword = $this->db->getConnection()->real_escape_string($newPassword);

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $query = "UPDATE utilisateurs SET mot_de_passe='$hashedPassword' WHERE email='$email'";
        $result = $this->db->getConnection()->query($query);

        return $result;
    }

    public function checkIfUserIsInscrit($userId, $coursId)
    {
        $userId = $this->db->real_escape_string($userId);
        $coursId = $this->db->real_escape_string($coursId);

        $query = "SELECT * FROM inscription_cours WHERE id_utilisateur=$userId AND id_cours=$coursId";
        $result = $this->db->query($query);

        if (!$result) {
            die("Erreur lors de la vérification de l'inscription : " . $this->db->error);
        }

        return $result->num_rows > 0;
    }

    public function getInscritCours($userId)
    {
        $userId = $this->db->real_escape_string($userId);

        $query = "SELECT *
                FROM inscription_cours
                JOIN cours ON inscription_cours.id_cours = cours.id_cours
                WHERE inscription_cours.id_utilisateur = '$userId'
                ";

        $result = $this->db->query($query);

        if (!$result) {
            die("Erreur d'exécution de la requête : " . $this->db->error);
        }

        $coursInscrits = [];

        while ($row = $result->fetch_assoc()) {
            $coursInscrits[] = $row;
        }

        return $coursInscrits;
    }

    public function validateResetToken($email, $token)
    {
        $email = $this->db->getConnection()->real_escape_string($email);
        $token = $this->db->getConnection()->real_escape_string($token);
        $query = "SELECT * FROM utilisateurs WHERE email='$email' AND reset_token='$token'";

        $result = $this->db->getConnection()->query($query);

        if (!$result) {
            die("Erreur d'exécution de la requête : " . $this->db->getConnection()->error);
        }

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
