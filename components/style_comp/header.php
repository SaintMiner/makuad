<?php
    // date_default_timezone_set("Latvia/Riga");
    // include("../../classes/category.php");
    include("/usbwebserver/makuad/version_h/classes/category.php");
    // include("../../classes/db_connection.php");

    if(isset($_GET["search"])) {
        if (empty($_GET["search"])) {
            header("Location: .");
        } else {
            header("Location: search?query=".$_GET["search"]);
        }
        echo "keeeeeeel";
    }
    

    if($_SERVER["QUERY_STRING"] == "logout") {
        unset($_SESSION["logged"]);
        session_unset();
        header("Location: .");
    }

    if ($_SESSION["logged"]) {
        $logUser = $_SESSION["logged"];
    }

    $categories = Category::getCategories();
?>
<header>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>    
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">

            <div class="navbar-start">
                <a class="navbar-item" href=".">
                    Galvena
                </a>
                <a class="navbar-item" href="/search?fPopular=2&filteredSearch=search">
                    Interesants
                </a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Kategorijas
                    </a>
                    <div class="navbar-dropdown">
                        <?php  foreach($categories as $category): ?>
                            <a class="navbar-item" href=<?="search?category=".$category->getID()?>>
                                <?=$category->getName(); ?>
                            </a>
                        <?php  endforeach; ?>
                    </div>
                </div>
            </div>

            <form class="navbar-item" action="" method="GET">
                <div class="field has-addons">
                    <div class="control">
                        <input class="input" name="search" type="text" placeholder="Meklesana">
                    </div>
                    <div class="control">
                        <input class="button is-info" type="submit" value="Meklet">
                    </div>
                </div>
            </form>

            <div class="navbar-end">
                
                
                <div class="navbar-item">
                    <a class="button has-text-white is-primary is-rounded custom-button" href="createAd">
                        <i class="fas fa-plus "></i>
                    </a>
                    <div id="username">
                        <?php $logUser ? $logUser->getUsername() : ""; ?>
                    </div>
                    <div class="buttons">
                        <?php if(!$logUser): ?>
                        
                        <a class="button is-primary" href="registration">
                            <strong>Pieregistreties</strong>
                        </a>
                        <a class="button is-light" href="login">
                            Pietekties
                        </a>
                        <?php else: ?>
                        <a class="button has-text-white is-primary is-rounded custom-button" href="profile">
                            <i class="fas fa-user "></i>
                        </a>
                            <?php if($_SESSION["logged"]->getRole() == "admin"): ?>
                                <a class="button has-text-white is-primary is-rounded custom-button" href="adminpanel">
                                    <i class="fas fa-wrench "></i>
                                </a>
                            <?php endif; ?>
                        
                        <a class="button is-light" href=<?php echo $_SERVER["PHP_SELF"]."?logout"?>>
                            Atteikties
                        </a>
                        
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>

        </div>

    </nav>
    <hr>
</header>