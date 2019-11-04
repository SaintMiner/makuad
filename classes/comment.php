<?php
    include("./db_connection.php");
    class Comment {
        private $comment;
        private $date;
        private $author;
        private $adLink;
        
        public function __construct($comment, $date, $author, $adLink) {
            $this->comment = $comment;
            $this->date = $date;
            $this->author = $author;
            $this->adLink = $adLink;
        }

        public function getComment() {
            return $this->comment;
        }
        
        public function getDate() {
            return $this->date;
        }

        public function getAuthor() {
            $db = new DB_connection();
            $sql = "SELECT username FROM users WHERE ID = '$this->author'";
            $author_name = $db->makeQuery($sql);
            return $author_name[0]["username"];
        }

        public function addComment() {
            $db = new DB_connection();
            // echo "THIS IS: ". $this->getComment();
            $sql = "INSERT INTO comments (comment, date, author, advertisement) VALUES ('$this->comment', '$this->date', '$this->author', '$this->adLink')";
                
            $db->makeInsertQuery($sql);
            

            $db = null;
            unset($db);
        }

        public static function getAdComments($adID) {
            $db = new DB_connection();

            $sql = "SELECT * FROM comments WHERE advertisement = '$adID' ORDER BY ID DESC";

            $res = $db->makeQuery($sql);
            
            $db = null;
            unset($db);
            
            return $res;
        }

    }

?>

