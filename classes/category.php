<?php 
    include("./db_connection.php");
    class Category {
        private $ID;
        private $name;

        public function __construct($ID, $name) {
            $this->ID = $ID;
            $this->name = $name;
        }

        public function getName() {
            return $this->name;
        }

        public function getID() {
            return $this->ID;
        }

        public static function getCategories() {
            $db_con = new DB_connection();
            $sql = "SELECT * FROM categories";
            $res = $db_con->makeQuery($sql);
            // print_r($res);
            $categories = array();
            foreach($res as $cat) {
                $buff = new Category($cat["ID"], $cat["Name"]);
                array_push($categories, $buff);
            }
            $db_con = null;
            unset($db_con);
            return $categories;
        }

        public static function getCategoryNameByID($ID) {
            $db_con = new DB_connection();
            $sql = "SELECT name FROM categories WHERE ID = '$ID'";
            $res = $db_con->makeQuery($sql);
            $db_con = null;
            unset($db_con);
            return $res[0]["name"];
        }



    }

?>
