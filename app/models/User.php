<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /* CREATE USER */
    public function createUser($fullName, $email, $password, $role)
    {
        $sql = "INSERT INTO users (fullName, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $fullName, $email, $password, $role);

        return $stmt->execute();
    }

    /* CHECK EMAIL EXISTS */
    public function emailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /* LOGIN */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);

        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}