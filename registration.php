<?php 
    include("./classes/db_connection.php");
    
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();

    if ($_SESSION["logged"]) {
        header("Location: index");
    }
    // if(isset($_POST['submit'])) {
    //     echo htmlspecialchars($_POST['email']);
    //     echo htmlspecialchars($_POST['username']);
    // }
    $errors = array("email"=>"", "username"=>"", "password"=>"", "c_password"=>"", "t_email"=>"", "t_username"=>"");
    
    if(isset($_POST['submit'])) {
        $email = $_POST['email'];
        if(empty($email)) {
            $errors["email"] = 'Lūdzu aizpildiet e-pasta lauku <br/>';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Ievaditais e-pasts nav korekts";
        } else {
            $errors["email"] = "";
        }
        
        $username = $_POST['username'];
        if(empty($username)) {
            $errors["username"] = 'Lūdzu aizpildiet lietotajvārdu lauku <br/>';
        } elseif (!preg_match('/^[a-zA-Z\d\_-]{4,32}$/', $username)) {
            $errors["username"] = "Lietotajvārds var saturet lielus un mazus latiņburtus, ciparus un '–', '_' zīmes. Jabūt vismaz 4 un ne vairak par 32 simboliem";
        } else {
            $errors["username"] = "";
        }

        $password = $_POST['password'];
        if(empty($password)) {
            $errors["password"] = "Ludzu aipildiet paroles lauku <br/>";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,50}$/', $password)) {
            $errors["password"] = "Prolei jasatur: <br/> 8 simbolus | Max: 50 simboli<br/> 1 lielu burtu <br/> 1 mazu burtu <br/> 1 ciparu. ";
        } else {
            $errors["password"] = "";
        }

        $c_password = $_POST["c_password"];
        if (empty($c_password)) {
            $errors["c_password"] = "Lūdzam apstiprinat paroli! <br/>";
        } elseif ($c_password != $password) {
            $errors["c_password"] = "Paroles nesakrit <br/>";
        } else {
            $errors["c_password"] = "";
        }
        $db_user = new User($username, $email, NULL);
        if (!empty($username) || !empty($email)) {
            
            // $errors["taken"] = $db_user->checkExistUser();
            $taken = $db_user->checkExistUser();
            if (!$taken["t_email"]) {
                $errors["t_email"] = "E-pasts ir aiņemts";
            } else {
                $errors["t_email"] = "";
            }
            if (!$taken["t_username"]) {
                $errors["t_username"] = "Lietotajvārds ir aizņemts";
            } else {
                $errors["t_username"] = "";
            }
        }
        
        if(array_filter($errors)) { //check for errors
            // echo "errors in form!";
        } else {
            $db_user->registerUser(hash('ripemd160',$password));
            // $db_con = new DB_connection();    
            // $query_res = $db_con->makeQuery("SELECT ID FROM users WHERE username LIKE \"first_user\"");
            // unset($db_con);
            
            // if ($checkableUser->checkExistUser($checkableUser)) {

            // } 
            header("Location: ./index");
            // header("Location: ./registration.php");

        }
    }

    

?>
<!DOCTYPE html>
<html>
<?php 
    include('./components/style_comp/head.php');
 ?>
<body>
<?php
    include('./components/style_comp/header.php');
 ?>
<div class="columns is-mobile is-multiline is-centered">
    <div class="column is-half box">
            <form class="field" action="registration" method="POST">

            <h4 class="has-text-centered">Lietotaja reģistracija</h4>
            
            <label class="label">Lietotajvārds</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Jūsu lietotajvārds" name="username" value="<?php echo $username?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-user"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-check"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["username"]?></span>


            <label class="label">E-pasts</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="email" placeholder="Jūsu e-pasts" name="email" value="<?php echo $email?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["email"]?></span>

            <label class="label">Parole</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="password" placeholder="Jūsu parole" name="password" value="<?php echo $password?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["password"]?></span>


            <label class="label">Paroles apstiprinašana</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="password" placeholder="Apstipriniet paroli   " name="c_password" value="">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php print $errors["c_password"]?></span>
            
            <span class="help is-danger"><?php print $errors["t_email"]?></span>
            <span class="help is-danger"><?php print $errors["t_username"]?></span>
            <br>
            <div class="is-centered buttons">
                <input class="button " type="submit" name="submit" value="Reģistreties">
            </div>
            
        </form>
    </div>
</div>
<?php
    include('./components/style_comp/footer.php');
 ?>
</body>
</html>