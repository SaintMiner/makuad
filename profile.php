<?php 
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();
    if (isset($_GET["user"])) {
        $id = $_GET["user"];
        $db = new DB_connection();
        $sql = "SELECT ID, username, email, role FROM users WHERE ID = '$id'";
        $res = $db->makeQuery($sql)[0];
        $profile = new User($res["username"], $res["email"], $res["ID"]);
        $userAds = $profile->getUserAdvertisements();
        $db = null;
        unset($db);
    } elseif (!$_SESSION["logged"]) {
        header("Location: login");
    } else {
        $profile = $_SESSION["logged"];
        $userAds = $profile->getUserAdvertisements();
        // print_r($userAds);
    }
    // print_r($userAds[0]);
    // echo $_GET["delete"];
    if(isset($_GET["delete"])) {
        $ID = $_GET["delete"];
        $deleteAd = $userAds[$ID];
        if ($deleteAd->getUserID() == $profile->getID()) {
            $deleteAd->deleteAdvetisement();
            // echo "delete: ".$ID;
            header("Location: profile");
        }
    } 
    if (isset($_GET["edit"])) {
        $ID = $_GET["edit"];
        $editAd = $userAds[$ID];
        header("Location: createAd?edit=".$editAd->getID());
    }

    

?>


<!DOCTYPE html>
<html lang="en">
<?php 
    include('components/style_comp/head.php');
?>
<body>
    <?php 
        include('components/style_comp/header.php');
    ?>
    <div id="confirm" class="modal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="container box">
                <div class="has-text-centered">Jūs tišam gribat izdest šo sludinajumu?</div>
                <br>
                <div class="buttons is-centered">
                    <div id="apply" class="button is-success" onclick="deleteTrue()">JĀ</div>
                    <div class="button is-danger" onclick="deleteFalse()">NĒ</div>
                </div>
            </div>
        </div>
        <button class="modal-close is-large" aria-label="close" onclick="deleteFalse()"></button>
    </div>
    <div class="columns box">
        <div class="column ">
            <div class="subtitle container">
                <?php $profile->getUsername();?>
            </div>
            <div class="container image is-128x128">
                <img src="https://bulma.io/images/placeholders/128x128.png" alt="User avatar">
            </div>
            <div class="">

            </div>
            
        </div>
        <div class="column is-three-quarters box columns is-multiline is-centered">
            <div class="notification has-text-centered is-primary title column is-full">
                JŪSU SLUDINAJUMI
            </div>
            <?php foreach($userAds as $key => $ad):?>
                <div class="card container column is-four-fifths box">
                    <div class="subtitle"><?=$ad->getTitle(); ?></div>
                    
                    <div class="adv-body card">
                        <div class="image is-96x96 is-pulled-left box" >
                                <img src="./img/<?=$ad->getLogo(); ?>" alt="Advertisement Logo">
                        </div>
                        <?=$ad->getSInfo(); ?></div>
                    <div class="level ">
                        <div class="level-left">
                        <?php  if(!isset($_GET["user"])): ?>

                            <div class="buttons container makuad-small-pad">
                            
                            <a class="button has-text-info box" href="<?="?edit=".$key ;?>">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a class="button has-text-info box" onclick="deleteConfirm(<?=$key?>)" >
                                <i class="fas fa-trash"></i>
                            </a>
                            </div>
                        <?php  endif; ?>
                        </div>
                        <div class="level-right">Datums: <?php  $ad->getCreatedAt(); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php 
        include('components/style_comp/footer.php');
    ?>
</body>
</html>