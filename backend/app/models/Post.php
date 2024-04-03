<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class Post {
  private PDO $db;

  public function __construct(PDO $db) {
    $this->db = $db;
  }

  public function create(int $id, string $title, string $filename) {
    $sql = "INSERT INTO posts (author, title, file_name) VALUES (:id, :title, :filename)";
    $args = [':id' => $id, ':title' => $title, ':filename' => $filename];
    $stmt = $this->db->prepare($sql);
    $stmt->execute($args);
    return 0;
  }

  public function read(int $num, int $page) {
    $sql = "SELECT p.id, name AS author, title, file_name, post_date FROM posts p, users u WHERE author = u.id ORDER BY post_date DESC LIMIT :start, :num";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":start" => $page*$num, ":num" => $num]);
    $result = [];
    for($i = 0; $i<$num; $i++){
      $row = $stmt->fetch();
      if($row) array_push($result, $row);
      else break;
    }
    return $result;
  }

  public function row_count() {
    $sql = "SELECT COUNT(id) FROM posts";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchColumn();
    return $rows;
  }
}