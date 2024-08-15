<?php
class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $title;
    public $level;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO ". $this->table_name ."SET title=:title, level=:level";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->level = htmlspecialchars(strip_tags($this->level));

        $stmt->bitParam(":title", $this->title);
        $stmt->bitParam(":level", $this->level);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE ". $this->table_name ."SET title=:title, level=:level WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->level = htmlspecialchars(strip_tags($this->level));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bitParam(":title", $this->title);
        $stmt->bitParam(":level", $this->level);
        $stmt->bitParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM". $this->table_name ."WHERE id=:id";
        $stmt =  $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bitParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>