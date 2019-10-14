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
    include("./classes/user.php");
    include("./classes/advertisement.php");
    $db_con = new DB_connection();
    $query_users_result = $db_con->makeQuery("SELECT ID, username, email FROM users;");
    $users = array(); //make array for user objects
    foreach ($query_users_result as $user) { //pushing object to array
        array_push($users, new User($user["username"], $user["email"], $user["ID"]) );
    }

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
                
            <!-- <?php for($i = 0; $i < count($users); $i++): ?> 
                <div class="container column is-one-third">
                    <h6 class="has-text-centered card label">USER: <?php echo htmlspecialchars($users[$i]->getUsername())?></h6>
                    <div class="card">
                        <p><span class="has-text-weight-medium"> ID: </span> <span class="is-pulled-right"> <?php echo $users[$i]->getID() ?> </span></p>
                        <p><span class="has-text-weight-medium"> USERNAME: </span> <span class="is-pulled-right"> <?php $users[$i]->getUsername()?> </span></p>
                        <p><span class="has-text-weight-medium"> E-MAIL: </span> <span class="is-pulled-right"> <?php $users[$i]->getEmail()?> </span></p>
                    </div>
                </div>
            <?php endfor; ?>  -->
            <?php foreach($users as $user): ?> 
                <div class="container column is-one-third">
                    <h6 class="has-text-centered card label">USER: <?php echo htmlspecialchars($user->getUsername())?></h6>
                    <div class="card">
                        <p><span class="has-text-weight-medium"> ID: </span> <span class="is-pulled-right"> <?php $user->getID() ?> </span></p>
                        <p><span class="has-text-weight-medium"> USERNAME: </span> <span class="is-pulled-right"> <?php $user->getUsername()?> </span></p>
                        <p><span class="has-text-weight-medium"> E-MAIL: </span> <span class="is-pulled-right"> <?php $user->getEmail()?> </span></p>
                    </div>
                </div>
            <?php endforeach; ?> 

            </div>
        </div>

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