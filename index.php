<?php
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();

    
    $db_con = new DB_connection();
    $adCount = $db_con->makeQuery("SELECT COUNT(*) as adCount FROM advertisements")[0]["adCount"];
    
    $pageCount = ceil($adCount/15);
    // echo $adCount." \n ".$pageCount;
    // $pageCount = 40;

    if (isset($_GET["page"])) {
        $curPage = $_GET["page"];
        echo "setted: ".$curPage;
    } else {
        $curPage = 1;
    }
    $adQueryCount = 15*($curPage-1);
    $query_ad_result = $db_con->makeQuery("SELECT * FROM advertisements ORDER BY ID DESC LIMIT $adQueryCount,15;");
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
                        <div class="image is-96x96 is-pulled-left makuad-logo-padding">
                            <img src="./img/<?=$ad->getLogo(); ?>" alt="Advertisement Logo">
                        </div>
                        <div>
                            <div class="columns is-variable is-10">
                                <div class="column">
                                <?php $ad->getShortInfo(); ?>
                                </div>
                            </div>
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
    <br>
    <?php if($pageCount):?>
    <div class="columns columns is-half is-centered">
        <nav class="pagination is-centered" role="navigation" aria-label="pagination">
            <?php if($pageCount > 5):?>
                <?php if($curPage != 1):?>
                    <a class="pagination-previous" href="?page=<?=$curPage-1?>">Previous</a>
                <?php endif;?>
                <?php if($curPage != $pageCount):?>
                    <a class="pagination-next" href="?page=<?=$curPage+1?>">Next page</a>
                <?php endif;?>
                <ul class="pagination-list">
                    <?php for($page = $curPage-2; $page <= $curPage+2; $page++):?>
                        <?php if($page > 0 && $page <= $pageCount): ?>
                            <li><a class="pagination-link <?= $curPage == $page ? "is-current" : ""?>" href="?page=<?=$page?>"><?=$page?></a></li>
                        <?php else: continue; endif;?>    
                    <?php endfor; ?>
                </ul>
            <?php elseif($pageCount == 1): ?>
                <ul class="pagination-list">
                    <li><a class="pagination-link is-current">1</a></li>
                </ul>
            <?php elseif($pageCount > 1 && $pageCount <= 5): ?>
                <?php if($curPage != 1):?>
                    <a class="pagination-previous" href="?page=<?=$curPage-1?>">Previous</a>
                <?php endif;?>
                <?php if($curPage != $pageCount):?>
                    <a class="pagination-next" href="?page=<?=$curPage+1?>">Next page</a>
                <?php endif;?>
                <ul class="pagination-list">
                    <?php for($page = 1; $page <=  $pageCount; $page++):?>
                        <li><a class="pagination-link <?= $page == $curPage ? "is-current" : ""?>" href="?page=<?=$page?>"><?=$page?></a></li>
                    <?php endfor; ?>
                </ul>
            <?php endif;?>
        </nav>
    </div>
    <?php endif?>
    <?php 
        include('components/style_comp/footer.php');
    ?>
</body>
</html>