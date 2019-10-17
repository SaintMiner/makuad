<?php 
    include("./classes/user.php");
    include("./classes/db_connection.php");
    
    $errors = array("email" => "", "password" => "", "login" => "");
    if (isset($_POST["submit"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if (empty($email)) {
            $errors["email"] = 'Please, Fill in email field';
        }
        if (empty($password)) {
            $errors["password"] = 'Please, Fill in password field';
        }
        
        if (!array_filter($errors)) {
            $logUser = User::login($email, $password);
            if ($logUser == NULL) {
                $errors["login"] = "Incorrect email or password! Please try again!";
            } else {
                session_start();
                $_SESSION["logged"] = $logUser;
                header("Location: index.php");
            }
            
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<?php include("./components/style_comp/head.php") ?>
<body>
    <?php include("./components/style_comp/header.php") ?>

    
    <div class="columns is-centered">
        
        <div class="box column is-half">
        <form action="login.php" class="field" method="POST">
            <h4 class="has-text-centered">USER LOGIN</h4>
            
            <label class="label">E-Mail</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Your Email" name="email" value="<?php echo $email?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="icon has-text-danger is-small is-right">
                    <!-- <i class="fas  fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["email"]?></span>
            
            <label class="label">Password</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="password" placeholder="Password input" name="password">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?php echo $errors["password"]?></span>
            <span class="help is-danger"><?php echo $errors["login"]?></span>

            <span class="help is-danger"><?php ?></span>
            <br>
            <div class="is-centered buttons">
                <input class="button " type="submit" name="submit" value="Login">
            </div>


        </form>
        </div>
    </div>


    <?php include("./components/style_comp/footer.php") ?>
</body>
</html>
