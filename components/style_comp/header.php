<?php
    date_default_timezone_set("Latvia/Riga");
    session_start();

    

    if($_SERVER["QUERY_STRING"] == "logout") {
        // unset($_SESSION["logged"]);
        session_unset();
    }
    if ($_SESSION["logged"]) {
        $logUser = $_SESSION["logged"];
    }
?>
<header>
    <nav class="navbar" role="navigation" aria-label="main navigation">
    

        <div id="navbarBasicExample" class="navbar-menu">

            <div class="navbar-start">
                <a class="navbar-item" href="index.php">
                    Home
                </a>
                <a class="navbar-item">
                    Interesants (Soon)
                </a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        More
                    </a>
                    <div class="navbar-dropdown">
                        <a class="navbar-item">
                            Par Mums
                        </a>
                        <hr class="navbar-divider">
                        <a class="navbar-item">
                            Report
                        </a>
                    </div>
                </div>
            </div>


            <div class="navbar-end">
                
                
                <div class="navbar-item">
                    <a class="button has-text-white is-primary is-rounded custom-button" href="createAd.php">
                        <i class="fas fa-plus "></i>
                    </a>
                    <div id="username">
                    <?php $logUser ? $logUser->getUsername() : ""; ?>
                    </div>
                    <div class="buttons">
                        <?php if(!$logUser): ?>
                        
                        <a class="button is-primary" href="registration.php">
                            <strong>Sign up</strong>
                        </a>
                        <a class="button is-light" href="login.php">
                            Log in
                        </a>
                        <?php else: ?>
                        <a class="button is-light" href=<?php echo $_SERVER["PHP_SELF"]."?logout"?>>
                            Log out
                        </a>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>

        </div>

    </nav>
    <hr>
</header>