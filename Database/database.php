<?php
class Database
{
    private $conn;

    public function __construct($host, $username, $password, $database)
    {
        $this->conn = new mysqli($host, $username, $password, $database);

        if ($this->conn->connect_error) {
            die("La connexion a échoué : " . $this->conn->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}
?>
