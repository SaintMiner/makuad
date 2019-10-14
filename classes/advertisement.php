<?php 
    include("./db_connection.php");
    class Advertisement {
        private $id;
        private $title;
        private $user;
        private $createAt;

        public function __construct($id, $title, $user, $createAt) {
            $this->id = $id;
            $this->title = $title;
            $this->user = $user;
            $this->createAt = $createAt;
        }

        public function getId() {
            echo $this->id;
        }

        public function getTitle() {
            echo $this->title;
        }

        public function getUser() {
            $db_con = new DB_connection();
            $username = $db_con->makeQuery("SELECT username FROM users WHERE ID = " . $this->user);

            $db_con = null;
            unset($db_con);

            echo $username[0]["username"];
        }

        public function getCreatedAt() {
            echo $this->createAt;
        }

        public function getShortInfo() {
            $db_con = new DB_connection();
            $shortInfo = $db_con->makeQuery("SELECT fullInfo FROM adinfo WHERE advertisement = " . $this->id);

            $db_con = null;
            unset($db_con);

            echo $shortInfo[0]["fullInfo"];
        }
    }
?>
