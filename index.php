<?php
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();

    $db_con = new DB_connection();
    $query_ad_result = $db_con->makeQuery("SELECT * FROM advertisements ORDER BY ID DESC;");
    $ads = array();
    foreach ($query_ad_result as $ad) {
        $buff = new Advertisement($ad["ID"], $ad["title"], $ad["user"], $ad["createdAt"], $ad["logo"], $ad["category"]);
        $buff->setViews($ad["views"]);
        array_push($ads, $buff);
    }

    if(isset($_POST["rate"])) {
        $ads[$_POST["adKey"]]->rateAD($_SESSION["logged"]->getID(), $ads[$_POST["adKey"]]->getID());
        header("Location: .");
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    include('components/style_comp/head.php');
?>
<body>
    <script>
        function getDetails(adID) {
            window.location.href = "/adInfo?id="+adID;
            // console.log(adID);
        }
    </script>
    <?php 
        include('components/style_comp/header.php');
    ?>
    

    <div>
        <span>
            Hi! Nothing to load, but all is working right (Maybe)!
        </span>
        <br>
        

        <div class="box">
            <div class="columns is-multiline">
            <?php foreach($ads as $key => $ad): ?> 
                <div class="container column is-one-third">
                    
                    <h6 class="has-text-centered card label has-background-primary"><?php $ad->getTitle();?></h6>
                    
                    <div onClick="getDetails(<?php echo $ad->getID()?>)" class="card adv-body">
                        <div class="image is-96x96 is-pulled-left box" >
                            <img src="./img/<?=$ad->getLogo(); ?>" alt="Advertisement Logo">
                        </div>
                        <div>
                            <p >
                                <?php $ad->getShortInfo(); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="card ">
                        <span class=" is-clipped adv-foot-username">  
                            <span class="has-text-weight-medium"> Created by </span> <?php $ad->getUser(); ?>
                        </span>
                        <span class="is-pulled-right">
                            <span class="has-text-weight-medium"> Date: </span> <?php $ad->getCreatedAt();  ?>
                        </span>
                    </div>

                    <div class="card level">
                        <div class="level-left">
                            <span><i class="fas fa-eye"></i></span>
                            <span><?=$ad->getViews(); ?></span>
                        </div>
                        <div class="level-item">
                            <?php if($_SESSION["logged"]):?>
                                <form method="POST">
                                    <input type="hidden" value=<?=$key?> name="adKey">
                                    <input class="button is-primary is-small" type="submit" value="<?= $ad->isRated($_SESSION["logged"]->getID()) ? "not cool" : "cool"?>" name="rate">
                                </form>
                            <?php endif;?>
                        </div>
                        <div class="level-right">
                            <span><i class="fas fa-thumbs-up"></i></span>
                            <span><?=$ad->getRating()?></span>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?> 
            </div>
        </div>
    </div>
    <?php 
        include('components/style_comp/footer.php');
    ?>
</body>
</html>