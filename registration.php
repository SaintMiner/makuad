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
            $errors["email"] = 'Please, fill the email! <br/>';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Not correct email";
        } else {
            $errors["email"] = "";
        }
        
        $username = $_POST['username'];
        if(empty($username)) {
            $errors["username"] = 'Please, fill the username <br/>';
        } elseif (!preg_match('/^[a-zA-Z0-9\s]+$/', $username)) {
            $errors["username"] = "Username can contain only uppercase and lowercase chars, number and spaces. Nedds to be 8 symbols minimum and 30 max";
        } else {
            $errors["username"] = "";
        }

        $password = $_POST['password'];
        if(empty($password)) {
            $errors["password"] = "Please, fill the password <br/>";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,50}$/', $password)) {
            $errors["password"] = "Password must contain: <br/> 8 Symbols | Max: 50 symbols<br/> 1 Uppercase char <br/> 1 Lowercase char <br/> 1 number. ";
        } else {
            $errors["password"] = "";
        }

        $c_password = $_POST["c_password"];
        if (empty($c_password)) {
            $errors["c_password"] = "Please, confirm your password <br/>";
        } elseif ($c_password != $password) {
            $errors["c_password"] = "Passwords do not match <br/>";
        } else {
            $errors["c_password"] = "";
        }
        $db_user = new User($username, $email, NULL);
        if (!empty($username) || !empty($email)) {
            
            // $errors["taken"] = $db_user->checkExistUser();
            $taken = $db_user->checkExistUser();
            if (!$taken["t_email"]) {
                $errors["t_email"] = "Email is already taken";
            } else {
                $errors["t_email"] = "";
            }
            if (!$taken["t_username"]) {
                $errors["t_username"] = "Username is already taken";
            } else {
                $errors["t_username"] = "";
            }
        }
        
        if(array_filter($errors)) { //check for errors
            echo "errors in form!";
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

            <h4 class="has-text-centered">USER REGISTRATION</h4>
            
            <label class="label">Username</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Text input" name="username" value="<?php echo $username?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-user"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-check"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["username"]?></span>


            <label class="label">Email</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="email" placeholder="Email input" name="email" value="<?php echo $email?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["email"]?></span>

            <label class="label">Password</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="password" placeholder="Password input" name="password" value="<?php echo $password?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["password"]?></span>


            <label class="label">Confirm Password</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="password" placeholder="Confirm password" name="c_password" value="<?php echo $c_password?>">
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
                <input class="button " type="submit" name="submit" value="Register">
            </div>
            
        </form>
    </div>
</div>
<?php
    include('./components/style_comp/footer.php');
 ?>
</body>
</html>