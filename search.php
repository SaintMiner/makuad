<?php 
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();

    if(isset($_GET["query"])) {
        $query = $_GET["query"];
        // echo $query;
        $db_con = new DB_connection();
        $sql = "SELECT * FROM advertisements WHERE MATCH(title, shortInfo, fullInfo) AGAINST('$query')";
        $query_ad_result = $db_con->makeQuery($sql);
        $ads = array();
        foreach ($query_ad_result as $ad) {
            array_push($ads, new Advertisement($ad["ID"], $ad["title"], $ad["user"], $ad["createdAt"], $ad["logo"]));
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <?php include("./components/style_comp/head.php"); ?>
<body>
    <script>
        function getDetails(adID) {
            window.location.href = "/adInfo.php?id="+adID;
        }
    </script>
    <?php include("./components/style_comp/header.php"); ?>
    <h1 class="title has-text-centered">Your search: "<span class="has-text-info"><?=$query?></span>" results!</h1>
    <div class="box">
        <?php if(empty($ads)):?>
            <div class="subtitle has-text-centered">
                    Sorry i cant find anything :(
            </div>
        <?php else:?>
        <div class="columns is-multiline">
            
                
            <?php foreach($ads as $ad): ?> 
                <div class="container column is-one-third">
                    
                    <h6 class="has-text-centered card label has-background-primary"><?php $ad->getTitle();?></h6>
                    
                    <div onClick="getDetails(<?php echo $ad->getID()?>)" class="card adv-body">
                        <div class="image is-96x96 is-pulled-left box" >
                            <img src="./img/<?php $ad->getLogo(); ?>" alt="Advertisement Logo">
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
            <?php endif;?>
        </div>
    </div>


    <?php include("./components/style_comp/footer.php"); ?>
</body>
</html>
