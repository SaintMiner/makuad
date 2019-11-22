<?php 
    include("./classes/db_connection.php");
    include("./classes/advertisement.php");
    include("./classes/user.php");
    include("./classes/comment.php");    
    session_start();
    
    if ($_SESSION["logged"]) {
        header("Location: .");
    }
    
    $errors = array("email" => "", "password" => "", "login" => "");
    if (isset($_POST["submit"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        if (empty($email)) {
            $errors["email"] = 'Lūdzu aizpildiet e-pasta lauku';
        }
        if (empty($password)) {
            $errors["password"] = 'Lūdzu aizpildiet paroles lauku';
        }
        
        if (!array_filter($errors)) {
            $logUser = User::login($email, hash('ripemd160', $password));
            if ($logUser == NULL) {
                $errors["login"] = "Nepareizi ievadits e-pasts vai parole. Ludzam pameģinat velreiz!";
            } else {
                $_SESSION["logged"] = $logUser;
                header("Location: .");
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
            <h4 class="has-text-centered">Lietotaja pieteikšana</h4>
            
            <label class="label">E-Pasts</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="text" placeholder="Jūsu e-pasts" name="email" value="<?=$email?>">
                <span class="icon is-small is-left">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="icon has-text-danger is-small is-right">
                    <!-- <i class="fas  fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?=$errors["email"]?></span>
            
            <label class="label">Parole</label>
            <div class="control has-icons-left has-icons-right">
                <input class="input" type="password" placeholder="Jūsu parole" name="password">
                <span class="icon is-small is-left">
                    <i class="fas fa-lock"></i>
                </span>
                <span class="icon is-small is-right">
                    <!-- <i class="fas fa-exclamation-triangle"></i> -->
                </span>
            </div>
            <span class="help is-danger"><?=$errors["password"]?></span>
            <span class="help is-danger"><?=$errors["login"]?></span>

            <span class="help is-danger"><?php ?></span>
            <br>
            <div class="is-centered buttons">
                <input class="button " type="submit" name="submit" value="Pieteikties">
            </div>


        </form>
        </div>
    </div>


    <?php include("./components/style_comp/footer.php") ?>
</body>
</html>
