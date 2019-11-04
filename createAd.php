<?php  
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");

    $err_name = array( array("title","title"), array("sInfo","short info"), array("fInfo","full info"));
    $errors = array("title" => "", "sInfo" => "", "fInfo" => "", "logo"=>"");
    if(isset($_POST["submit"])) {

        foreach($err_name as $err) {
            if(empty($_POST[$err[0]])) {
                $errors[$err[0]] = "Please, fill in ".$err[1]." field.";
            }
        }

        if (strlen($_POST["sInfo"]) > 300) {
            $errors["sInfo"] = "Short info max symbol count 300!";
        }
        

        if (!array_filter($errors)) {
            session_start();
            
            // htmlspecialchars_decode
            
            $title = addslashes($_POST["title"]);
            $sInfo = addslashes($_POST["sInfo"]);
            $buff = addslashes($_POST["fInfo"]);
            $fInfo = <<<MARKER
$buff
MARKER;
            // echo $title;
            // $fInfo = htmlspecialchars($_POST["fInfo"]);
            $date = date("20y-m-d", time());

            $ad = new Advertisement(NULL, $title, $_SESSION["logged"]->getID(), $date, NULL);
            $file_id = date("ymdU").rand(1,1000);
            // echo $file_id;
            $errors["logo"] = $ad->uploadFile($_FILES["fileToUpload"], $file_id);
            echo $errors["logo"];
            if (!array_filter($errors)) {
                $ad->createAdvertisement($sInfo, $fInfo);
                header("Location: ./index.php");
            }
            
        }
    }
    //     $target_dir = "img/";
    //     $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    //     $uploadOk = 1;
    //     $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    //     // Check if image file is a actual image or fake image
    //     if(isset($_POST["submit"])) {
    //         $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    //         if($check !== false) {
    //             echo "File is an image - " . $check["mime"] . ".";
    //             $uploadOk = 1;
    //         } else {
    //             echo "File is not an image.";
    //             $uploadOk = 0;
    //         }
    //     }
    // if (empty($_FILES["fileToUpload"]["name"])) {
    //     echo "NO FILE CHOOSED STANDART";
    // } else {
    //     // Check if file already exists
    //     if (file_exists($target_file)) {
    //         echo "Sorry, file already exists.";
    //         $uploadOk = 0;
    //     }
    //     // Check file size
    //     if ($_FILES["fileToUpload"]["size"] > 500000) {
    //         echo "Sorry, your file is too large.";
    //         $uploadOk = 0;
    //     }
    //     // Allow certain file formats
    //     if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    //         echo "Sorry, only JPG, JPEG, PNG files are allowed.";
    //         $uploadOk = 0;
    //     }
    //     // Check if $uploadOk is set to 0 by an error
    //     if ($uploadOk == 0) {
    //         echo "Sorry, your file was not uploaded.";
    //     // if everything is ok, try to upload file
    //     } else {
    //         if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //             echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    //         } else {
    //             echo "Sorry, there was an error uploading your file.";
    //         }
    //     }
    // }
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./components/style_comp/head.php"); ?>
<body>
    <script>
        function setfilename(val) {
            var fileName = val.substr(val.lastIndexOf("\\")+1, val.length);
            document.getElementById("fileToUpload").value = fileName;
        }
    </script>
    <?php include("./components/style_comp/header.php"); ?>
    <?php if (!$_SESSION["logged"]): header("Location: login.php")?>
    <?php else: ?>
        <div class="columns is-centered">
            <div class="column is-half box">
                <form action="createAd.php" method="POST" enctype="multipart/form-data">

                    <label class="label">Title</label>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="text" placeholder="Title" name="title" value="<?php echo  $_POST["title"]?>">
                        <span class="icon is-small is-left">
                            <i class="fas fa-align-justify"></i>
                        </span>
                        <span class="icon is-small is-right">
                        <!-- <i class="fas fa-exclamation-triangle"></i> -->
                        </span>
                    </div>
                    <span class="help is-danger"><?php print $errors["title"]?></span>

                    <label class="label">Short Info [300 max]</label>
                    <div class="control has-icons-left has-icons-right">
                        <textarea class="textarea" name="sInfo" rows="3" ><?php echo $_POST["sInfo"]?></textarea>
                    </div>
                    <span class="help is-danger"><?php print $errors["sInfo"]?></span>

                    <label class="label">Full Info</label>
                    <div class="control has-icons-left has-icons-right">
                        <textarea class="textarea" name="fInfo" rows="5"><?php echo $_POST["fInfo"]?></textarea>
                    </div>
                    <span class="help is-danger"><?php print $errors["fInfo"]?></span>

                    <label class="label">Logo</label>
                    <div class="file">
                        <label class="file-label">
                            <input class="file-input" type="file" name="fileToUpload" onchange="setfilename(this.value);" value="">
                            <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                                Choose a file…
                            </span>
                                <input id="fileToUpload" name="uploadFileOne" type="text" disabled="disabled" placeholder="" class="file-name" />
                            </span>
                        </label>
                    </div>
                    <div class="image is-96x96 is-pulled-left box" >
                        <img src="./img/makuad_logo.png" alt="no">
                    </div>
                    <span class="help is-danger"><?php print $errors["logo"]?></span>

                    <br>
                    <div class="is-centered buttons">
                        <input class="button is-primary" type="submit" name="submit" value="Create">
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
    
    


    <?php include("./components/style_comp/footer.php"); ?>
</body>
</html>