<?php

declare(strict_types=1);

namespace App\Models;

class Post extends BaseModel {
  public function create(int $id, string $title, string $filename) {
    $sql = "INSERT INTO posts (author, title, file_name) VALUES (:id, :title, :filename)";
    $args = [':id' => $id, ':title' => $title, ':filename' => $filename];
    $stmt = $this->db->prepare($sql);
    $stmt->execute($args);
    return 0;
  }

  public function read_by_id(int $id, int | null $userId = null) {
    $sql = "SELECT p.id, u.name AS author, p.title, p.file_name, p.post_date, COALESCE(SUM(r.value), 0) AS ratio
            FROM posts p LEFT JOIN reactions r ON p.id = r.post LEFT JOIN users u ON p.author = u.id
            WHERE p.id = :id
            GROUP BY p.id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(); 

    if(!$result) return false;

    $sql = "SELECT t.name FROM post_tag pt LEFT JOIN tags t ON pt.tag = t.id WHERE pt.post = :post";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":post" => $id]);
    $tags = $stmt->fetchAll(\PDO::FETCH_COLUMN);
    $result['tags'] = $tags;

    if($userId) {
      $sql = "SELECT value FROM reactions WHERE post = :post AND user = :userId";
      $stmt = $this->db->prepare($sql);
      $stmt->execute([":post" => $id, ":userId" => $userId]);
      $vote = $stmt->fetch();
      $result['vote'] = $vote ? $vote['value'] : 0;
    }

    return $result;
  }

  public function read_latest(int $num, int $page, int | null $userId = null) {
    $sql = "SELECT p.id, u.name AS author, p.title, p.file_name, p.post_date, COALESCE(SUM(r.value), 0) AS ratio
            FROM posts p LEFT JOIN reactions r ON p.id = r.post LEFT JOIN users u ON p.author = u.id
            GROUP BY p.id ORDER BY p.post_date DESC LIMIT :start, :num";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":start" => $page*$num, ":num" => $num]);
    $result = [];

    if($userId) {
      $sql = "SELECT value FROM reactions WHERE post = :post AND user = :userId";
      $stmt2 = $this->db->prepare($sql);
    }

    $sql = "SELECT t.name FROM post_tag pt LEFT JOIN tags t ON pt.tag = t.id WHERE pt.post = :post";
    $stmt3 = $this->db->prepare($sql);

    for($i = 0; $i<$num; $i++){
      $row = $stmt->fetch();
      if($row) {
        array_push($result, $row);
        $stmt3->execute([":post" => $result[$i]['id']]);
        $tags = $stmt3->fetchAll(\PDO::FETCH_COLUMN);
        $result[$i]['tags'] = $tags;
        if($userId){
          $stmt2->execute([":post" => $result[$i]['id'], ":userId" => $userId]);
          $vote = $stmt2->fetch();
          $result[$i]['vote'] = $vote ? $vote['value'] : 0;
        }
      }
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

  public function read_reaction(int $post, int $user) {
    $sql = "SELECT * FROM reactions WHERE post = :post AND user = :user";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":post" => $post, ":user" => $user]);
    return $stmt->fetch();
  }

  public function read_ratio(int $post) {
    $sql = "SELECT COALESCE(SUM(value), 0) AS ratio FROM reactions WHERE post = :post";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":post" => $post]);
    return $stmt->fetchColumn();
  }

  public function create_reaction(int $post, int $user, int $value) {
    $sql = "INSERT INTO reactions (post, user, value) VALUES (:post, :user, :value)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":post" => $post, ":user" => $user, ":value" => $value]);
    return 0;
  }

  public function update_reaction(int $post, int $user, int $value) {
    $sql = "UPDATE reactions SET value = :value WHERE post = :post AND user = :user";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":value" => $value, ":post" => $post, ":user" => $user]);
    return 0;
  }

  public function delete_reaction(int $post, int $user) {
    $sql = "DELETE FROM reactions WHERE post = :post AND user = :user";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":post" => $post, ":user" => $user]);
    return 0;
  }

  public function add_tag(int $post, int $tag) {
    $sql = "INSERT INTO post_tag (post, tag) VALUES (:post, :tag)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':post' => $post, ':tag' => $tag]);
    return 0;
  }
}