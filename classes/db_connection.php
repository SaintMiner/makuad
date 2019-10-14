<?php  
    class DB_connection {
        protected $server = "localhost";
        protected $user = "root";
        protected $password = "usbw";
        protected $database = "makuad";

        public function __construct() {
            $this->con = new mysqli($this->server, $this->user, $this->password, $this->database);
        }

        public function __destruct() {
            $this->con->close();
        }

        public function makeQuery($sql) { //make query function | return query result
            $result = $this->con->query($sql);
            $fetchedResult = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            return $fetchedResult;
        }

        // public function selectUsers() {
        //     $sql = "SELECT ID, username, email FROM users";
        //     $result = $this->con->query($sql);
        //     $rs = $result->fetch_all(MYSQLI_ASSOC);
        //     $result->free();
        //     return $rs;
        // }

    }
?>