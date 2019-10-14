<?php
    include("./db_connection.php");
    class User {
        private $username;
        private $email;
        private $id;

        public function __construct($username, $email, $id) {
            $this->id = $id;
            $this->username = $username;
            $this->email = $email;
        }

        
        
        public function getID() {
            echo $this->id;
        }

        public function getUsername() {
            echo $this->username;
        }

        public function getEmail() {
            echo $this->email;
        }

        public function checkExistUser() {
            $db_con = new DB_connection();
            $query_username = $db_con->makeQuery("SELECT ID FROM users WHERE username LIKE  '$this->username'");
            $query_email = $db_con->makeQuery("SELECT ID FROM users WHERE email LIKE  '$this->email'");

            $db_con = null;
            unset($db_con);

            $error = array("t_email"=>true,"t_username"=>true);
            if (array_filter($query_username)) {
                // $error_help .= "This username is alredy taken <br />";
                $error["t_username"] = false;
            }
            if (array_filter($query_email)) {
                // $error_help .= "This email is alredy taken <br />";
                $error["t_email"] = false;
            }

            return $error;
        }

        public function registerUser($password) {
            $db_con = new DB_connection();
            $sql = "INSERT INTO users (username, password, email) VALUES ('$this->username', '$password', '$this->email')asd";
            $db_con->makeInsertQuery($sql);

            $db_con = null;
            unset($db_con);
        }
        
    }
?>

