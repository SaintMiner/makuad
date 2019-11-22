<?php 
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();

    $db_con = new DB_connection();
    $ads = array();

    

    function loadAds($query_ad_result) {
        $ads = array();
        foreach ($query_ad_result as $ad) {
            $buff = new Advertisement($ad["ID"], $ad["title"], $ad["user"], $ad["createdAt"], $ad["logo"], $ad["category"]);
            $buff->setShortInfo($ad["shortInfo"]);
            array_push($ads, $buff);
        }
        return $ads;
    }

    if (isset($_GET["query"]) && isset($_GET["category"])) {
        $query = $_GET["query"];
        $category = $_GET["category"];
        $sql = "SELECT ad.ID, ad.logo, ad.shortInfo, ad.fullInfo, ad.user, ad.createdAt, ad.title, ad.category 
        FROM advertisements AS ad INNER JOIN users AS u ON u.ID = ad.user 
        WHERE category = '$category' AND (MATCH(ad.title, ad.shortInfo, ad.fullInfo) AGAINST('$query') OR  MATCH(U.username) AGAINST('$query'))
        ORDER BY ad.createdAT DESC";
    } elseif (isset($_GET["query"])) {
        $query = $_GET["query"];
        // echo $query;
        
        // $sql = "SELECT * FROM advertisements WHERE MATCH(title, shortInfo, fullInfo) AGAINST('$query')";
        $sql = "SELECT ad.ID, ad.logo, ad.shortInfo, ad.fullInfo, ad.user, ad.createdAt, ad.title, ad.category 
        FROM advertisements AS ad INNER JOIN users AS u ON u.ID = ad.user 
        WHERE MATCH(ad.title, ad.shortInfo, ad.fullInfo) AGAINST('$query') OR  MATCH(U.username) AGAINST('$query')
        ORDER BY ad.createdAT DESC";
        // $query_ad_result = $db_con->makeQuery($sql);
        // $ads = loadAds($query_ad_result);
        // print_r($ads);
    } elseif (isset($_GET["category"])) {
        $category = $_GET["category"];
        $sql = "SELECT * FROM advertisements WHERE category = '$category' ORDER BY createdAt DESC";
        // $query_ad_result = $db_con->makeQuery($sql);
        // $ads = loadAds($query_ad_result);
    }

    if (isset($_GET["query"]) || isset($_GET["category"])) {
        $query_ad_result = $db_con->makeQuery($sql);
        $ads = loadAds($query_ad_result);
    }

    if (isset($_GET["filteredSearch"])) {
        $fSearch = $_GET["fSearch"];
        $fCategory = $_GET["fCategory"];
        $fPopular = $_GET["fPopular"];
        $fUpload = $_GET["fUpload"];
        $select = "SELECT ad.ID, ad.title, ad.fullInfo, ad.shortInfo, ad.createdAt, ad.category, ad.logo, ad.user 
        FROM advertisements AS ad INNER JOIN users AS u ON u.ID = ad.user ";
        if (!empty($fSearch) || !empty($fCategory) || $fPopular == 2 ) {
            $where = "WHERE ";
        }
        if (!empty($fSearch)) {
            $where .= "MATCH(ad.title, ad.shortInfo, ad.fullInfo) AGAINST('$fSearch') OR  MATCH(U.username) AGAINST('$fSearch') ";
        }
        if (!empty($fCategory)) {
            if (!empty($fSearch)) {
                $where .= "AND ";
            }
            $where .= "category = '$fCategory' ";
        }

        if ($fPopular == 2) {
            if (!empty($fSearch) || !empty($fCategory)) {
                $where .= "AND ";
            }
        
            $where .= " createdAt BETWEEN NOW() - INTERVAL 1 MONTH - INTERVAL 1 DAY AND NOW()";
        }

        switch ($fUpload) {
            case 1: {
                $where .= "ORDER BY ad.createdAt DESC";
                break;
            }
            case 2: {
                $where .= "ORDER BY ad.createdAt ASC";
                break;
            }
        }

        if ($fPopular == 2) {
            if (empty($fUpload)) {
                $where .= "ORDER BY ";
            } else {
                $where .= ", ";
            }
            $where .= "views DESC";
        }
        // echo $select.$where;
        $res = $db_con->makeQuery($select.$where);
        $ads = loadAds($res);
    }
    $db_con = null;
    unset($db_con);

    if(isset($_POST["rate"])) {
        $ads[$_POST["adKey"]]->rateAD($_SESSION["logged"]->getID(), $ads[$_POST["adKey"]]->getID());
        header("Location: ?fCategory=".$fCategory."&fUpload=".$fUpload."&fPopular=".$fPopular."&filteredSearch=".$fSearch."&category=".$category);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include("./components/style_comp/head.php"); ?>
<body>
    <?php include("./components/style_comp/header.php"); ?>
    <?php if(isset($_GET["query"])): ?>
        <h1 class="title has-text-centered">Jūsu meklēšanas: "<span class="has-text-info"><?=$query?></span>" rezultats!</h1>
    <?php endif;?>
    <?php if(isset($_GET["category"])): ?>
        <h2 class="subtitle has-text-centered">Kategorijas: "<span class="has-text-warning"><?=Category::getCategoryNameByID($_GET["category"])?></span>" rezultats!</h2>
    <?php endif;?>

    <form action="" method="GET">
        <label class="label has-text-centered">Filtreta meklēšana</label>
        <div class="field has-addons has-addons-centered">
        <div class="control select">
                <select name="fPopular" id="" class="">
                    <option value="0" selected disabled>Populars</option>
                    <option value="1" <?= $fPopular == 1 ? "selected" : ""?>>Viss</option>
                    <option value="2" <?= $fPopular == 2 ? "selected" : ""?>>Interesants</option>
                </select>
            </div>
            <div class="control select">
                <select name="fUpload" id="" class="">
                    <option value="0" selected disabled>Pievienots</option>
                    <option value="1" <?= $fUpload == 1 ? "selected" : ""?>>Jauns</option>
                    <option value="2" <?= $fUpload == 2 ? "selected" : ""?>>Vecs</option>
                </select>
            </div>
            <div class="control select">
                <select name="fCategory" id="" class="">
                    <option value="0" selected disabled>Kategorijas</option>
                    <?php foreach(Category::getCategories() as $category):?>
                        <option value="<?=$category->getID()?>" <?= isset($_GET["category"]) || $fCategory ? $_GET["category"] == $category->getID() || $fCategory == $category->getID() ? "selected" : "" : "" ?>><?=$category->getName()?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="control">
                <input class="input" name="fSearch" type="text" placeholder="Search" value="<?=$_GET["fSearch"]?>">
            </div>
            <div class="control">
                <input class="button is-info" type="submit" name="filteredSearch" value="search">
            </div>
        </div>
    </form>
    
    <div class="box">
        <?php if(empty($ads)):?>
            <div class="subtitle has-text-centered">
                    Atvainojiet mēs neko neatradam
            </div>
        <?php else:?>
        <div class="columns is-multiline">
            
            <?php foreach($ads as $key => $ad): ?> 
                <div class="container column is-one-third">
                    
                    <h6 class="has-text-centered card label has-background-primary"><?=$ad->getTitle();?></h6>
                    
                    <div onClick="getDetails(<?=$ad->getID()?>)" class="card adv-body">
                        <div class="image is-96x96 is-pulled-left makuad-logo-padding">
                            <img src="./img/<?=$ad->getLogo(); ?>" alt="Advertisement Logo">
                        </div>
                        <div>
                            <p >
                                <?=$ad->getSInfo(); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="card ">
                        <span class=" is-clipped adv-foot-username">  
                            <span class="has-text-weight-medium"> Izveidoja: </span> <?php $ad->getUser(); ?>
                        </span>
                        <span class="is-pulled-right">
                            <span class="has-text-weight-medium"> Datums: </span> <?php $ad->getCreatedAt();  ?>
                        </span>
                    </div>

                    <div class="card level">
                        <div class="level-item">
                            <?php if($_SESSION["logged"]):?>
                                <form method="POST">
                                    <input type="hidden" value=<?=$key?> name="adKey">
                                    <input class="button is-primary is-small" type="submit" value="<?= $ad->isRated($_SESSION["logged"]->getID()) ? "nepatik" : "patik"?>" name="rate">
                                </form>
                            <?php endif;?>
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
