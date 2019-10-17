<?php  
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");

    $err_name = array( array("title","title"), array("sInfo","short info"), array("fInfo","full info"));
    $errors = array("title" => "", "sInfo" => "", "fInfo" => "");
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
            
            $title = $_POST["title"];
            $sInfo = $_POST["sInfo"];
            $fInfo = $_POST["sInfo"];
            $date = date("20y-m-d", time());

            $ad = new Advertisement(NULL, $title, $_SESSION["logged"]->getID(), $date);
            
            $ad->addAdvertisement($sInfo, $fInfo);
        }

        
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./components/style_comp/head.php"); ?>
<body>
    <?php include("./components/style_comp/header.php"); ?>
    <?php if (!$_SESSION["logged"]): header("Location: login.php")?>
    <?php else: ?>
        <div class="columns is-centered">
            <div class="column is-half box">
                <form action="createAd.php" method="POST">

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
