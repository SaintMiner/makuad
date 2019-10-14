<?php
 
    $kek = "Cheburek";
        
    // $conn = mysqli_connect("localhost", "root", "usbw", "makuad");

    // if(!$conn) {
    //     echo "Connection error: " . mysqli_connect_error();
    // }

    // // function getUsers() {
    //     $sql = "SELECT * FROM users";
    //     $result = mysqli_query($conn, $sql);
    //     $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //     print_r($users);
    //     mysqli_free_result($result);
    
    //     mysqli_close($conn);
    
    //     print_r($users);

    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    $db_con = new DB_connection();
    

    $query_ad_result = $db_con->makeQuery("SELECT ID, title, user, createdAt FROM advertisements;");
    $ads = array();
    foreach ($query_ad_result as $ad) {
        array_push($ads, new Advertisement($ad["ID"], $ad["title"], $ad["user"], $ad["createdAt"]));
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php 
    include('components/style_comp/head.php');
    include('components/style_comp/header.php');
?>
<body>
    <div>
        <span>
            Hi! Nothing to load, but all is working right (Maybe)!
        </span>
        <br>
        

        <div class="box">
            <div class="columns is-multiline">
            <?php foreach($ads as $ad): ?> 
                <div class="container column is-one-third">
                    <h6 class="has-text-centered card label has-background-primary"><?php $ad->getTitle(); ?></h6>
                    
                    <div class="card" id="ad-body">
                        <p>
                            <?php $ad->getShortInfo(); ?>
                            
                        </p>
                    </div>

                    <div class="card">
                        <span>  
                            <span class="has-text-weight-medium"> Created by </span> <?php $ad->getUser(); ?>
                        </span>
                        <span class="is-pulled-right">
                             <span class="has-text-weight-medium"> Date: </span> <?php $ad->getCreatedAt();  ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?> 
            </div>
        </div>
    </div>
    <?php 
        // include('components/activity_comp/registration.php');
        include('components/style_comp/footer.php');
    ?>
</body>
</html>