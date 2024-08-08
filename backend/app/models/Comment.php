<?php

declare(strict_types=1);

namespace App\Models;

class Comment extends BaseModel {
  public function create(int $post, int $user, int | null $replyTo, string $content) {
    $sql = "INSERT INTO comments (post, author, reply_to, content) VALUES (:post, :user, :reply_to, :content)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":post" => $post, ":user" => $user, ":reply_to" => $replyTo, ":content" => $content]);
    return 0;
  }

  public function read_by_post(int $post) {
    $sql = "SELECT c.id, u.name AS author, c.author AS author_id, c.reply_to, c.content, c.post_date FROM comments c LEFT JOIN users u ON c.author = u.id WHERE c.post = :post ORDER BY c.post_date ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":post" => $post]);
    $result = [];
    
    $row = $stmt->fetch();
    while($row) {
      array_push($result, $row);
      $row = $stmt->fetch();
    }

    return $result;
  }
}