<?php
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");

    if(isset($_GET["id"])) {
        $adId =  $_GET["id"];
        $db_con = new DB_connection();
        $sql = "SELECT * FROM advertisements WHERE ID = '$adId'";
        $res = $db_con->makeQuery($sql)[0];
        $ad = new Advertisement($res["ID"], $res["title"], $res["user"], $res["createdAt"], $res["logo"]);
        $ad->setShortInfo($res["shortInfo"]);
        $ad->setFullInfo($res["fullInfo"]);
        // echo $res["fullInfo"];  
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
    
    <div class="columns is-centered">
        <div class="column is-two-thirds">
            <div class="title has-text-centered">
                <?php $ad->getTitle(); ?>
            </div>
            <div class="box">
                <?php $ad->getFullInfo(); ?>
            </div>
        </div>
    </div>

    <div class="columns is-centered">
        <div class="column is-three-fifths box">
            <h1 class="subtitle has-text-centered">Comments</h1>

            <article class="media">
                <div class="media-left image is-64x64">
                    <img src="https://bulma.io/images/placeholders/128x128.png" alt="test">
                </div>    
                <div class="media-content">
                    <p>
                        <span>
                            <strong>John Smith</strong>
                        </span>
                        <span class="is-pulled-right">
                            <small> Date: 20-05-2019 17:18</small>
                        </span>
                    </p>
                    <p>
                        Debating me breeding be answered an he.
                        Spoil event was words her off cause any. Tears woman which no is world miles woody.
                        Wished be do mutual except in effect answer.
                    </p>
                </div>
            </article>
            

        </div>
    </div>

    

    <?php 
        include('components/style_comp/footer.php');
    ?>
</body>
</html>