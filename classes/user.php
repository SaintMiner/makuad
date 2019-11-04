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
            return $this->id;
        }

        public function getUsername() {
            echo $this->username;
        }

        public function getEmail() {
            echo $this->email;
        }
        
        public function getUserAdvertisements() {
            $db_con = new DB_connection()            ;
            
            $sql = "SELECT * FROM advertisements WHERE user = '$this->id' ORDER BY ID DESC";
            $userAds = array();

            $query_ads = $db_con->makeQuery($sql);
            
            foreach($query_ads as $ad) {
                $buff = new Advertisement($ad["ID"], $ad["title"], $ad["user"], $ad["createdAt"], $ad["logo"]);
                $buff->setFullInfo($ad["fullInfo"]);
                $buff->setShortInfo($ad["shortInfo"]);
                array_push($userAds, $buff);
            }

            $db_con = null;
            unset($db_con);

            return $userAds;
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
            $sql = "INSERT INTO users (username, password, email) VALUES ('$this->username', '$password', '$this->email')";
            $db_con->makeInsertQuery($sql);

            $db_con = null;
            unset($db_con);
        }

        public static function login($email, $password) {
            $db_con = new DB_connection();
            $sql = "SELECT id, username, email FROM users WHERE '$password' = password AND '$email' = email";
            $query_rs = $db_con->makeQuery($sql);
            $db_con = null;
            unset($db_con);
            if ($query_rs == NULL) {
                return NULL;
            } else {
                return new User($query_rs[0]["username"], $query_rs[0]["email"], $query_rs[0]["id"]);
            }
            // print_r($query_rs);
            // return $query_rs;
        }
        
    }
?>

