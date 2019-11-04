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
            if($result) {
                // echo "Selected very GOOD!";
            } else {
                echo "query error (" .   $this->con->errno . "):" . $this->con->error;
            }
            $fetchedResult = $result->fetch_all(MYSQLI_ASSOC);
            $result->free();
            return $fetchedResult;
        }

        public function makeDeleteQuery($sql) {
            $result = $this->con->query($sql);
            if($result) {
                // echo "Selected very GOOD!";
            } else {
                echo "query error (" .   $this->con->errno . "):" . $this->con->error;
            }            
        }

        public function makeInsertQuery($sql) {
            $result = $this->con->query($sql);
            if($result) {
                // echo "Inserted very GOOD!";
                return $this->con->insert_id;
            } else {
                echo "query error (" .   $this->con->errno . "):" . $this->con->error;
            }
        }

    }
?>
