<?php

declare(strict_types=1);

namespace App\Models;

class Tag extends BaseModel {
  public function create(string $tag) {
    $sql = "INSERT INTO tags (name) VALUES (:tag)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":tag" => $tag]);
    return 0;
  }

  public function read(string $name) {
    $sql = "SELECT * FROm tags WHERE name = :name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':name' => $name]);
    return $stmt->fetch();
  }
}