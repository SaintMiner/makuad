<?php
    include("./db_connection.php");
    class Comment {
        private $text;
        private $date;
        private $author;
        private $linkedAD;

        public function getText() {
            return $this->text;
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

        public function addComment($adID) {

        }

    }

?>

