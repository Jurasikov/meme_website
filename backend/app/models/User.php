<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class User extends BaseModel {
  public function create(string $username, string $password, bool $admin) : int {
    $sql = "INSERT INTO users (name, passwd, administrator_rights) VALUES (:username, :password, :admin)";
    $args = [':username' => $username, ':password' => password_hash($password, PASSWORD_BCRYPT), ':admin' => (int) $admin];
    $stmt = $this->db->prepare($sql);
    $stmt->execute($args);
    return $this->read($username)['id'];
  }

  public function read(string $username) {
    $sql = "SELECT * FROM users WHERE name=:username";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':username' => $username]);
    $result = $stmt->fetch();
    return $result;
  }

  public function read_by_id(int $userId) {
    $sql = "SELECT * FROM users WHERE id=:userId";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    $result = $stmt->fetch();
    return $result;
  }
}