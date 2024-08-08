<?php

declare(strict_types=1);

namespace App\Models;

class BaseModel {
  protected \PDO $db;

  public function __construct(\PDO $db) {
    $this->db = $db;
  }

  public function beginTransaction() {
    return $this->db->beginTransaction();
  }

  public function commit() {
    return $this->db->commit();
  }

  public function rollBack() {
    return $this->db->rollBack();
  }

  public function lastInsertId() {
    return $this->db->lastInsertId();
  } 
}