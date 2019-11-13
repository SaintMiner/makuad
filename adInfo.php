<?php
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();

    $ad;
    if(isset($_GET["id"])) {
        $adId =  $_GET["id"];
        $db_con = new DB_connection();
        $sql = "SELECT * FROM advertisements WHERE ID = '$adId'";
        $res = $db_con->makeQuery($sql)[0];
        $ad = new Advertisement($res["ID"], $res["title"], $res["user"], $res["createdAt"], $res["logo"], $res["category"]);
        $ad->setShortInfo($res["shortInfo"]);
        $ad->setFullInfo($res["fullInfo"]);
        $ad->setViews($res["views"]);
        // echo $res["fullInfo"];  
        if(isset($_POST["rate"])) {
            // echo $ad->isRated($_SESSION["logged"]->getID());
            $ad->rateAD($_SESSION["logged"]->getID(), $ad->getID());
            header("Location: adInfo?id=".$ad->getID());
        } else {
            $ad->addView();
        }
        $comments = array();
        $db_comments = Comment::getAdComments($ad->getID());

        foreach($db_comments as $com) {
            array_push($comments, new Comment($com["comment"], $com["date"], $com["author"], $com["advertisement"]));
        }
    }
    

    
    
    // print_r($comments);

    if(isset($_POST["submit"])) {
        $commentText = $_POST["comment"];
        // echo $commentText;
        if (empty($commentText) || $commentText === " ") {
            echo "Empty comment";
        } else {
            if ($_SESSION["logged"]) {
                $date = date("20y-m-d H:i:s", time());
                $author = $_SESSION["logged"]->getID();
                $comment = new Comment($commentText, $date, $author, $ad->getID());
                // echo $comment->comment;
                $comment->addComment();
                unset($_POST["comment"]);
                header("Refresh:0");
            }
        }
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
            <div class="columns is-centered">
                <img src="./img/<?=$ad->getLogo(); ?>" class="image" alt="Advertisement Logo">
            </div>
            <div class="title has-text-centered">
                <?php $ad->getTitle(); ?>
            </div>
            <div class="level">
                <div class="level-left">
                    <span><i class="fas fa-eye"></i></span>
                    <span><?=$ad->getViews(); ?></span>
                </div>
                <div class="level-right">
                    <span><i class="fas fa-thumbs-up"></i></span>
                    <span><?=$ad->getRating()?></span>
                </div>
            </div>
            <div class="box">
                <div class="makuad-overflow-auto">
                    <?= $ad->getFullInfo(); ?>
                </div>
            </div>
            <div class="field buttons is-centered">
                <?php if($_SESSION["logged"]): ?>
                    <form action="" method="POST">
                        <input class="button is-primary" type="submit" value="<?= $ad->isRated($_SESSION["logged"]->getID()) ? "not cool" : "cool"?>" name="rate">
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="columns is-centered">
        <div class="column is-three-fifths box">
            <h1 class="subtitle has-text-centered">Comments</h1>

            <?php if($_SESSION["logged"]): ?> 
                <form  class="media" action="" method="POST">
                <figure class="media-left">
                    <p class="image is-64x64">
                    <img src="https://bulma.io/images/placeholders/128x128.png">
                    </p>
                </figure>
                <div class="media-content">
                    <div class="field">
                    <p class="control">
                        <textarea class="textarea" placeholder="Add a comment..." name="comment" ></textarea>
                    </p>
                    </div>
                    <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                        <input type="submit" class="button is-info" value="Submit" name="submit">
                        </div>
                    </div>
                    </nav>
                </div>
                </form>
            <?php endif;?>

            <?php foreach($comments as $com): ?>
            <article class="media">
                <div class="media-left image is-64x64">
                    <img src="https://bulma.io/images/placeholders/128x128.png" alt="test">
                </div>    
                <div class="media-content">
                    <p>
                        <span>
                            <strong> <?=$com->getAuthor(); ?></strong>
                        </span>
                        <span class="is-pulled-right">
                            <small> Date: <?=$com->getDate();?></small>
                        </span>
                    </p>
                    <p>
                        <?= $com->getComment(); ?>
                    </p>
                </div>
            </article>
            <?php endforeach;?>
            

        </div>
    </div>

    

    <?php 
        include('components/style_comp/footer.php');
    ?>
</body>
</html>