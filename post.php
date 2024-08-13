<?php
class Post {
    private $conn;
    private $table_name = "posts";

    public $id;
    public $title;
    public $content;
    public $author;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET title=:title, content=:content, author=:author";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->author = htmlspecialchars(strip_tags($this->author));

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":author", $this->author);

        if ($stmt->execute()) {
         $lastId = $this->conn->lastInsertId();

        // Prepare and execute a SELECT query to get the newly inserted record
        $selectQuery = "SELECT id, title, content, author FROM " . $this->table_name . " WHERE id = :id";
        $selectStmt = $this->conn->prepare($selectQuery);
        $selectStmt->bindParam(":id", $lastId);
        $selectStmt->execute();

        // Fetch the record
        $record = $selectStmt->fetch(PDO::FETCH_ASSOC);

        // Return the record
        return $record;
        }

        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->author = $row['author'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title = :title, content = :content, author = :author WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            $id = $this->id;

        // Prepare and execute a SELECT query to get the newly inserted record
        $selectQuery = "SELECT id, title, content, author FROM " . $this->table_name . " WHERE id = :id";
        $selectStmt = $this->conn->prepare($selectQuery);
        $selectStmt->bindParam(":id", $id);
        $selectStmt->execute();

        // Fetch the record
        $record = $selectStmt->fetch(PDO::FETCH_ASSOC);

        // Return the record
        return $record;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
