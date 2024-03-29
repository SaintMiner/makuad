<?php 
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();
    
    if ($_SESSION["logged"]->getRole() != "admin") {
        header("Location: .");
    }

    $db_con = new DB_connection();
    $adCount = $db_con->makeQuery("SELECT COUNT(*) as adCount FROM advertisements")[0]["adCount"];
    
    $pageCount = ceil($adCount/10);
    // echo $adCount." \n ".$pageCount;
    if (isset($_GET["page"])) {
        $curPage = $_GET["page"];
        // echo "setted: ".$curPage;
    } else {
        $curPage = 1;
    }
    $queryItemCount = 10*($curPage-1);
    $selectedTab = "Users";

    

    if(isset($_GET["tab"])) {
        $tab = $_GET["tab"];
        if ($tab == "Users" || $tab == "Advertisements") {
            $selectedTab = $tab;
        } else {
            $tab = "Users";
        }
    } else {
        $tab = "Users";
    }

    if (isset($_GET["banUser"])) {
        $id = $_GET["banUser"];
        User::blockUser($id);
        $banUser = $_GET["banUser"];
        header("Location: adminpanel?page=".$curPage."&tab=".$tab);
    }

    if ($tab == "Advertisements") {
        $query_ad_result = $db_con->makeQuery("SELECT * FROM advertisements ORDER BY ID DESC LIMIT $queryItemCount,10;");
        $ads = array();
        foreach ($query_ad_result as $ad) {
            $buff = new Advertisement($ad["ID"], $ad["title"], $ad["user"], $ad["createdAt"], $ad["logo"], $ad["category"]);
            $buff->setViews($ad["views"]);
            array_push($ads, $buff);
        }
    }
    if ($tab == "Users") {
        $sql = "SELECT ID, username, email, blocked, role FROM users ORDER BY ID DESC LIMIT $queryItemCount,10";
        $query_user_result = $db_con->makeQuery($sql);
        $users = array();
        foreach ($query_user_result as $user) {
            $buff = new User($user["username"], $user["email"], $user["ID"]);
            $buff->setBlocked($user["blocked"]);
            array_push($users, $buff);
        }
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<?php include("./components/style_comp/head.php"); ?>

<body>
    <?php include("./components/style_comp/header.php"); ?>
    <div id="banAdConfirm" class="modal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="container box">
                <div class="has-text-centered">Are you sure you want to ban this advertisement?</div>
                <br>
                <div class="buttons is-centered">
                    <div id="applyAd" class="button is-success" onclick="banAdTrue()">OK</div>
                    <div class="button is-danger" onclick="banAdFalse()">CANCEL</div>
                </div>
            </div>
        </div>
        <button class="modal-close is-large" aria-label="close" onclick="banAdFalse()"></button>
    </div>
    <div id="banUserConfirm" class="modal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="container box">
                <div class="has-text-centered">Are you sure you want to ban this user?</div>
                <br>
                <div class="buttons is-centered">
                    <div id="applyUser" class="button is-success" onclick="banUserTrue()">OK</div>
                    <div class="button is-danger" onclick="banUserFalse()">CANCEL</div>
                </div>
            </div>
        </div>
        <button class="modal-close is-large" aria-label="close" onclick="banUserFalse()"></button>
    </div>
    <div class="columns is-centered is-multiline">
        <div class="column is-four-fifths box">
            <div class="tabs is-centered">
                <ul>
                    <li class="<?= $selectedTab == "Users" ? "is-active" : ""?>"><a href="?tab=Users">Users</a></li>
                    <li class="<?= $selectedTab == "Advertisements" ? "is-active" : ""?>"><a href="?tab=Advertisements">Advertisements</a></li>
                </ul>
            </div>
            <?php if($tab == "Advertisements"): ?>
                <?php foreach($ads as $ad): ?>
            
                <div class="section">
                    <div class="container card">
                        
                        <div class="image is-96x96 is-pulled-left makuad-logo-padding">
                            <img src="./img/<?=$ad->getLogo(); ?>" alt="Advertisement Logo">
                        </div>
                        <div>
                        <div class="box is-marginless">
                            <div class="columns is-variable is-10 is-multiline">
                                <div class="column is-full">
                                    <?php $ad->getTitle(); ?>
                                </div>
                                <div class="column is-full makuad-overflow-auto">
                                    <?php $ad->getShortInfo(); ?>
                                </div>
                                
                            </div>
                        </div>
                            <div class="container is-fluid">
                            <span class=" is-clipped adv-foot-username">  
                                <span class="has-text-weight-medium"> Izveidoja:  </span> <?php $ad->getUser(); ?>
                            </span>
                            <span class="is-pulled-right">
                                <span class="has-text-weight-medium"> Datums: </span> <?php $ad->getCreatedAt();  ?>
                            </span>
                            
                            <div class="buttons container ">
                                <a class="button box" onclick="banAdConfirm(<?=$key?>)" >
                                    <i class="fas fa-ban"></i>
                                </a>
                                <a class="button box" onclick="openProfile(<?=$key?>)" >
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            </div>
                        </div>
                       
                    </div>
                    
                </div>
                <?php endforeach; ?>
            <?php elseif($tab == "Users"): ?>
                <?php  foreach($users as $key => $user): ?>
                    <div class="section">
                        <div class="container box">
                            <div class="card has-text-centered has-background-info has-text-white">
                                <?php $user->getUsername(); ?>
                            </div>
                            <div class="box">
                                <p>
                                    ID: <span class="is-pulled-right"><?=$user->getID(); ?></span>
                                </p>
                                <p>
                                    E-mail: <span class="is-pulled-right"><?php $user->getEmail(); ?></span>
                                </p>
                                <p>
                                    Blocked: <span class="is-pulled-right"><?=$user->getBlocked() ? "Yes" : "No"?></span>
                                </p>
                            </div>
                            <div class="buttons">
                                <a class="button has-text-info box" onclick="banUserConfirm(<?=$user->getID().','.$curPage.',\''.$tab.'\''?>)" >
                                    <i class="fas fa-ban"></i>
                                </a>
                                <a class="button has-text-info box" onclick="openProfile(<?=$user->getID()?>)" >
                                    <i class="fas fa-user"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
            <?php endif; ?>
        </div>
        
    </div>
    <?php if($pageCount):?>
    <div class="columns is-half is-centered box">
        <nav class="pagination is-centered" role="navigation" aria-label="pagination">
            <?php if($pageCount > 5):?>
                <?php if($curPage != 1):?>
                    <a class="pagination-previous" href="?page=<?=$curPage-1 ."&tab=".$tab?>">Previous</a>
                <?php endif;?>
                <?php if($curPage != $pageCount):?>
                    <a class="pagination-next" href="?page=<?=$curPage+1 ."&tab=".$tab?>">Next page</a>
                <?php endif;?>
                <ul class="pagination-list">
                    <?php for($page = $curPage-2; $page <= $curPage+2; $page++):?>
                        <?php if($page > 0 && $page <= $pageCount): ?>
                            <li><a class="pagination-link <?= $curPage == $page ? "is-current" : ""?>" href="?page=<?=$page ."&tab=".$tab?>"><?=$page?></a></li>
                        <?php else: continue; endif;?>    
                    <?php endfor; ?>
                </ul>
            <?php elseif($pageCount == 1): ?>
                <ul class="pagination-list">
                    <li><a class="pagination-link is-current">1</a></li>
                </ul>
            <?php elseif($pageCount > 1 && $pageCount <= 5): ?>
                <?php if($curPage != 1):?>
                    <a class="pagination-previous" href="?page=<?=$curPage-1 ."&tab=".$tab?>">Previous</a>
                <?php endif;?>
                <?php if($curPage != $pageCount):?>
                    <a class="pagination-next" href="?page=<?=$curPage+1 ."&tab=".$tab?>">Next page</a>
                <?php endif;?>
                <ul class="pagination-list">
                    <?php for($page = 1; $page <=  $pageCount; $page++):?>
                        <li><a class="pagination-link <?= $page == $curPage ? "is-current" : ""?>" href="?page=<?=$page ."&tab=".$tab?>"><?=$page?></a></li>
                    <?php endfor; ?>
                </ul>
            <?php endif;?>
        </nav>
    </div>
    <?php endif?>
    <?php include("./components/style_comp/footer.php"); ?>
</body>
</html>
