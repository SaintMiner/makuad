<?php 
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();

    if (!$_SESSION["logged"]) {
        header("Location: login.php");
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
            header("Location: profile.php");
        }
    } elseif (isset($_GET["edit"])) {
        $ID = $_GET["edit"];
        $editAd = $userAds[$ID];
        header("Location: createAd.php?edit=".$editAd->getID());
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
                Your Advertisements
            </div>
            <?php foreach($userAds as $key => $ad):?>
                <div class="card container column is-four-fifths box">
                    <div class="subtitle"><?php  $ad->getTitle(); ?></div>
                    
                    <div class="adv-body card">
                        <div class="image is-96x96 is-pulled-left box" >
                                <img src="./img/<?php $ad->getLogo(); ?>" alt="Advertisement Logo">
                        </div>
                        <?php  $ad->getSInfo(); ?></div>
                    <div class="level ">
                        <div class="level-left">
                            <div class="buttons container makuad-small-pad">

                            <a class="button has-text-info box" href="<?="?edit=".$key ;?>">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a class="button has-text-info box" href="<?="?delete=".$key ;?>">
                                <i class="fas fa-trash"></i>
                            </a>
                            </div>

                        </div>
                        <div class="level-right">Date: <?php  $ad->getCreatedAt(); ?></div>
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