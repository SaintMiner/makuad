<?php 
    include("./db_connection.php");
    class Advertisement {
        private $id;
        private $title;
        private $user;
        private $createdAt;

        public function __construct($id, $title, $user, $createdAt) {
            $this->id = $id;
            $this->title = $title;
            $this->user = $user;
            $this->createdAt = $createdAt;
        }

        public function getId() {
            echo $this->id;
        }

        public function getTitle() {
            echo $this->title;
        }

        public function getUser() {
            $db_con = new DB_connection();
            $username = $db_con->makeQuery("SELECT username FROM users WHERE ID = '$this->user'");

            $db_con = null;
            unset($db_con);

            echo $username[0]["username"];
        }

        public function getCreatedAt() {
            echo $this->createdAt;
        }

        public function getShortInfo() {
            $db_con = new DB_connection();
            $shortInfo = $db_con->makeQuery("SELECT shortInfo FROM adinfo WHERE advertisement = '$this->id'");

            $db_con = null;
            unset($db_con);

            echo $shortInfo[0]["shortInfo"];
        }

        public function addAdvertisement($sInfo, $fInfo) {
            $db_con = new DB_connection();
            $sql = "INSERT INTO advertisements (title, user, createdAt) VALUES ('$this->title','$this->user','$this->createdAt')";
            $db_con->makeInsertQuery($sql);

            $sql = "SELECT ID FROM advertisements WHERE '$this->title' = title AND '$this->user' = user AND '$this->createdAt' = createdAt";
            $this->id = $db_con->makeQuery($sql)[0]["ID"];

            $sql = "INSERT INTO adinfo (shortInfo, fullInfo, advertisement) VALUES ('$sInfo','$fInfo','$this->id')";
            $db_con->makeInsertQuery($sql);

            $db_con = null;
            unset($db_con);
        }
    }
?>
