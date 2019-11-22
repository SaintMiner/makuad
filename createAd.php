<?php  
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();
    $editAD = NULL;
    $editMode = false;
    
    if (!$_SESSION["logged"]) {
        header("Location: /login");
    } elseif ( $_SESSION["logged"]->isBlocked()) {
        header("Location: .");
    }

    if(isset($_GET["edit"])) {
        $db_con = new DB_connection();
        $editMode = true;
        $editID = $_GET["edit"];
        $sql = "SELECT * FROM advertisements WHERE ID = '$editID'";
        $res = $db_con->makeQuery($sql)[0];
        $editAD = new Advertisement($res["ID"], $res["title"], $res["user"], $res["createdAt"], $res["logo"], $res["category"]);
        $editAD->setFullInfo($res["fullInfo"]);
        $editAD->setShortInfo($res["shortInfo"]);
        $db_con = null;
        unset($db_con);
        if ($editAD->getUserID() != $_SESSION["logged"]->getID()) {
            header("Location: .");
        }
        // echo $editAD->getCategory();
    }

    $err_name = array( array("title"," virsraksta"), array("sInfo"," īsu informacijas"), array("fInfo"," pilnas informacijas"));
    $errors = array("title" => "", "sInfo" => "", "fInfo" => "", "logo"=>"");

    if(isset($_POST["submit"]) && !$editMode) {

        foreach($err_name as $err) {
            if(empty($_POST[$err[0]])) {
                $errors[$err[0]] = "Lūdzam, aipildit ".$err[1]."  lauku.";
            }
        }

        if (strlen($_POST["sInfo"]) > 300) {
            $errors["sInfo"] = "Īsu informaciju maksimalais simbolu ksaits ir 300!";
        }
        
        if (empty($_POST["fInfo"])) {
            $_POST["fInfo"] = $_POST["sInfo"];
            $errors["fInfo"] = "";
        }

        if (!array_filter($errors)) {
            $title = addslashes($_POST["title"]);
            $selCategory = $_POST["category"];
            $sInfo = addslashes($_POST["sInfo"]);
            $buff = addslashes($_POST["fInfo"]);
$fInfo = <<<MARKER
$buff
MARKER;
            $date = date("20y-m-d", time());
            $ad = new Advertisement(NULL, $title, $_SESSION["logged"]->getID(), $date, NULL, $selCategory);
            $file_id = date("ymdU").rand(1,1000);
            $errors["logo"] = $ad->uploadFile($_FILES["fileToUpload"], $file_id);
            // echo $errors["logo"];
            if (!array_filter($errors)) {
                $ad->createAdvertisement($sInfo, $fInfo);
                header("Location: ./index");
            }
        }
    }

    if (isset($_POST["save"]) && $editMode) {

        // echo "edit mode";
        
        foreach($err_name as $err) {
            if(empty($_POST[$err[0]])) {
                $errors[$err[0]] = "Lūdzam aipildit ".$err[1]." lauku.";
            }
        }

        if (strlen($_POST["sInfo"]) > 300) {
            $errors["sInfo"] = "Īsu informaciju maksimalais simbolu ksaits ir 300!";
        }

        if (empty($_POST["fInfo"])) {
            $_POST["fInfo"] = $_POST["sInfo"];
            $errors["fInfo"] = "";
        }

        $editAD->setShortInfo(addslashes($_POST["sInfo"]));
        $editAD->setFullInfo(addslashes($_POST["fInfo"]));
        $editAD->setTitle(addslashes($_POST["title"]));
        $editADLogo = "./img/".$editAD->getLogo();
        $editAD->setCategory($_POST["category"]);

        if (!array_filter($errors)) {
            if(!file_exists("./img/".$_FILES["fileToUpload"]["name"])) {
                $file_id = date("ymdU").rand(1,1000);
                $errors["logo"] = $editAD->uploadFile($_FILES["fileToUpload"], $file_id);
                if (!array_filter($errors)) {
                    if ($editADLogo != "./img/makuad_logo.png") {
                        unlink($editADLogo);
                        // echo $editADLogo;
                    }
                    $editAD->editSaveAdvertisement();
                    header("Location: ./index");
                    // echo "DONE!";
                }
            } else {
                $editAD->editSaveAdvertisement();
                header("Location: ./index");
            }
        }

    }


?>

<!DOCTYPE html>
<html lang="en">
<?php include("./components/style_comp/head.php"); ?>
<body>
    <script>
        function setfilename(input) {
            document.getElementById("fileToUpload").value = input.files[0].name;
            var reader = new FileReader();
            reader.onload = function () {
                document.getElementById("yourLogo").src = reader.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    </script>
    <?php include("./components/style_comp/header.php"); ?>
        <div class="columns is-centered">
            <div class="column is-half box">
                <form action="" method="POST" enctype="multipart/form-data">

                    <label class="label">Virsraksts</label>
                    <div class="control has-icons-left has-icons-right">
                        <input class="input" type="text" placeholder="Sludinajuma virsraksts" name="title" value="<?= !$editAD ? $_POST["title"] : $editAD->getTitle()?>">
                        <span class="icon is-small is-left">
                            <i class="fas fa-align-justify"></i>
                        </span>
                        <span class="icon is-small is-right">
                        <!-- <i class="fas fa-exclamation-triangle"></i> -->
                        </span>
                    </div>
                    <span class="help is-danger"><?php print $errors["title"]?></span>

                    <label class="label">Īsa informacija [300 max]</label>
                    <div class="control has-icons-left has-icons-right">
                        <textarea class="textarea" name="sInfo" rows="3" ><?=!$editAD ? $_POST["sInfo"] : $editAD->getSInfo()?></textarea>
                    </div>
                    <span class="help is-danger"><?php print $errors["sInfo"]?></span>

                    <label class="label">Pilna informacija</label>
                    <div class="control has-icons-left has-icons-right">
                        <textarea class="textarea" name="fInfo" rows="5"><?=!$editAD ? $_POST["fInfo"] : $editAD->getFullInfo()?></textarea>
                    </div>
                    <span class="help is-danger"><?php print $errors["fInfo"]?></span>

                    <label class="label">Logotips</label>
                    <div class="file level">
                        <label class="file-label">
                            <input class="file-input" type="file" name="fileToUpload" onchange="setfilename(this); " value="<?= $editAD ? "./img/".$editAD->getLogo() : $_FILES["fileToUpload"]?>">
                            <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                               Izveleties failu...
                            </span>
                                <input id="fileToUpload" name="uploadFileOne" type="text" disabled="disabled" class="file-name" value=""/>
                            </span>
                        </label>
                        <div class="image is-96x96 is-pulled-left box makuad-create-ad-logo" >
                            <img id="yourLogo" src="./img/<?= !$editAD ? "makuad_logo.png" : $editAD->getLogo()  ?>" alt="Your Advertisement Logo">
                        </div>
                    </div>
                    <label class="label">Kategorija</label>
                    <div class="select is-medium">
                        <select name="category" id="">
                            <?php foreach(Category::getCategories() as $category): ?>
                                <option value="<?=$category->getID()?>" <?= $editAD ? $editAD->getCategory() == $category->getID() ? "selected" : "" : "" ?>><?=$category->getName()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <span class="help is-danger"><?php print $errors["logo"]?></span>
                    <br>
                    <div class="is-centered buttons">
                        <?php  if(!$editAD): ?>
                            <input class="button is-primary" type="submit" name="submit" value="Izveidot">
                        <?php  else: ?>
                            <input class="button is-primary" type="submit" name="save" value="Saglabat">
                        <?php  endif; ?>
                    </div>
                </form>
            </div>
        </div>
    <?php include("./components/style_comp/footer.php"); ?>
</body>
</html>