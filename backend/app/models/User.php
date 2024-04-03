<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class User {
  private PDO $db;

  public function __construct(PDO $db) {
    $this->db = $db;
  }

  public function create(string $username, string $password, bool $admin) : String {
    $sql = "INSERT INTO users (name, passwd, administrator_rights) VALUES (:username, :password, :admin)";
    $args = [':username' => $username, ':password' => password_hash($password, PASSWORD_BCRYPT), ':admin' => $admin];
    $stmt = $this->db->prepare($sql);
    $stmt->execute($args);
    return "Dodano użyszkodnika";
  }

  public function read(string $username) {
    $sql = "SELECT * FROM users WHERE name=:username";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':username' => $username]);
    $result = $stmt->fetch();
    return $result;
  }
}