<?php 
    include("./db_connection.php");
    class Advertisement {
        private $id;
        private $title;
        private $user;
        private $createdAt;
        private $logo;
        private $sInfo;
        private $fInfo;
        private $category;
        private $views;
        
        public function __construct($id, $title, $user, $createdAt, $logo, $category) {
            $this->id = $id;
            $this->title = $title;
            $this->user = $user;
            $this->createdAt = $createdAt;
            $this->logo = $logo;
            $this->category = $category;
        }

        public function setCategory($category) {
            $this->category = $category;
        }

        public function setShortInfo($sInfo) {
            $this->sInfo = $sInfo;
        }

        public function setFullInfo($fInfo) {
            $this->fInfo = $fInfo;
        }
        
        public function setTitle($title) {
            $this->title = $title;
        }

        public function setViews($views) {
            $this->views = $views;
        }

        public function getViews() {
            return $this->views;
        }

        public function getCategory() {
            return $this->category;
        }

        public function getFullInfo() {
            return htmlspecialchars($this->fInfo);
        }

        public function getSInfo() {
            return htmlspecialchars($this->sInfo);
        }

        public function getLogo() {
            return $this->logo;
        }

        public function getID() {
            return $this->id;
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
        
        public function getUserID() {
            return $this->user;
        }

        public function getCreatedAt() {
            echo $this->createdAt;
        }

        public function getShortInfo() {
            $db_con = new DB_connection();
            $shortInfo = $db_con->makeQuery("SELECT shortInfo FROM advertisements WHERE ID = '$this->id'");

            $db_con = null;
            unset($db_con);

            echo $shortInfo[0]["shortInfo"];
        }


        public function createAdvertisement($sInfo, $fInfo) {
            $db_con = new DB_connection();
            $sql = "INSERT INTO advertisements (title, user, createdAt, shortInfo, fullInfo, logo, category) VALUES ('{$this->title}','$this->user','$this->createdAt', '{$sInfo}', '{$fInfo}', '$this->logo', '$this->category')";
            $ID = $db_con->makeInsertQuery($sql);
            $db_con = null;
            unset($db_con);
            return $ID;
        }
        
        public function editSaveAdvertisement() {
            $db_con = new DB_connection();
            $sql = "UPDATE advertisements SET logo = '$this->logo', title = '$this->title', shortInfo = '$this->sInfo', fullInfo = '$this->fInfo', category = '$this->category' WHERE ID = '$this->id'";
            $db_con->makeUpdateQuery($sql);
            $db_con = null;
            unset($db_con);
            // return $ID;
        }

        public function deleteAdvetisement() {
            $db_con = new DB_connection();
            $sql = "DELETE com.* FROM advertisements AS ad INNER JOIN comments as com ON com.advertisement = ad.ID WHERE ad.ID = '$this->id'";
            $db_con->makeDeleteQuery($sql);
            $sql = "DELETE FROM advertisements WHERE ID = '$this->id'";
            $db_con->makeDeleteQuery($sql);
            if ($this->logo != "makuad_logo.png") {
                unlink("./img/".$this->logo);
            }
            $db_con = null;
            unset($db_con);
        }

        public function addView() {
            $db_con = new DB_connection();
            $sql = "UPDATE advertisements SET views = views+1 WHERE ID = '$this->id'";
            $db_con->makeUpdateQuery($sql);
            $db_con = null;
            unset($db_con);
        }

        public function rateAD($user, $ad) {
            $db_con = new DB_connection();
            $rated = $this->isRated($user);
            if (!$rated) {
                $sql = "INSERT INTO userrate (user, ad) VALUES ('$user', '$ad')";
                $db_con->makeInsertQuery($sql);
            } else {
                $sql = "DELETE FROM userrate WHERE user = '$user' AND ad = '$ad'";
                $db_con->makeDeleteQuery($sql);
            }
            $db_con = null;
            unset($db_con);
        }

        public function isRated($user) {
            $db_con = new DB_connection();
            $sql = "SELECT count(*) as rated FROM `userrate` WHERE user = '$user' AND ad = '$this->id'";
            $rated = $db_con->makeQuery($sql);
            $db_con = null;
            unset($db_con);
            return $rated[0]["rated"];
        }

        public function getRating() {
            $db_con = new DB_connection();
            $sql = "SELECT count(*) as rated FROM `userrate` WHERE ad = '$this->id'";
            $rated = $db_con->makeQuery($sql);
            $db_con = null;
            unset($db_con);
            return $rated[0]["rated"];
        }

        public function uploadFile($file, $ID) {
            $target_dir = "img/";
            // $target_file = $target_dir . basename($file["name"]);
            // $file_upl = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($file["name"],PATHINFO_EXTENSION));
            $uploadingFile = $ID . "." . $imageFileType;
            $target_file = $target_dir . $uploadingFile;
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($file["tmp_name"]);
                if($check !== false) {
                    // echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    // echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            if (empty($file["name"])) {
                $this->logo = "makuad_logo.png";
                echo "NO FILE CHOOSED STANDART";
            } else {
                // Check if file already exists
                if (file_exists($target_file)) {
                    // echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }
                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 500000) {
                    return "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    return "Sorry, only JPG, JPEG, PNG files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($file["tmp_name"], $target_file)) {
                        $this->logo = $uploadingFile;
                        echo "The file ". basename( $file["name"]). " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                        return false;
                    }
                }
            }
        }

    }
?>