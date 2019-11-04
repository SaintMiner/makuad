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
        $ad = new Advertisement($res["ID"], $res["title"], $res["user"], $res["createdAt"], $res["logo"]);
        $ad->setShortInfo($res["shortInfo"]);
        $ad->setFullInfo($res["fullInfo"]);
        // echo $res["fullInfo"];  
    }
    
    $comments = array();
    $db_comments = Comment::getAdComments($ad->getID());

    foreach($db_comments as $com) {
        array_push($comments, new Comment($com["comment"], $com["date"], $com["author"], $com["advertisement"]));
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
                        <textarea class="textarea" placeholder="Add a comment..." name="comment" ><?php echo $_POST["comment"]?></textarea>
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
                            <strong> <?php  echo $com->getAuthor(); ?></strong>
                        </span>
                        <span class="is-pulled-right">
                            <small> Date: <?php  echo $com->getDate();?></small>
                        </span>
                    </p>
                    <p>
                        <?php  echo $com->getComment(); ?>
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