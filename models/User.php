<?php
class User {
    public $conn;
    public $id;
    public $username;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fetchUserByUsername() {
        $query = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($this->id, $this->username, $this->password, $this->role);
            $stmt->fetch();
            return true;
        }
        return false;
    }
}
?>
