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
        array_push($ads, new Advertisement($ad["ID"], $ad["title"], $ad["user"], $ad["createdAt"], $ad["logo"], $ad["category"]));
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
            window.location.href = "/adInfo.php?id="+adID;
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
            <?php foreach($ads as $ad): ?> 
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
                        <div class="level-item">
                            <button class="button is-small is-primary">
                               cool 
                            </button>
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