<nav>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
        echo '<div class="tasto_nav"><a href="home.php">Home</a></div>
        <div class="tasto_nav"><a href="registrazione.php">Registrazione</a></div>
        <div class="tasto_nav"><a href="bacheca.php" >Bacheca</a></div>
        <div class="tasto_nav"><a href="scrivi.php" >Scrivi</a></div>
        <div class="tasto_nav"><span href="login.php" >Login</span></div>
        <div class="tasto_nav"><a href="scopri.php">Scopri</a></div>
        <div class="tasto_nav"><a href="logout.php">Logout</a></div>';
    } else {    
        echo '<div class="tasto_nav"><a href="home.php">Home</a></div>
        <div class="tasto_nav"><a href="registrazione.php">Registrazione</a></div>
        <div class="tasto_nav"><span href="bacheca.php" >Bacheca</span></div>
        <div class="tasto_nav"><span href="scrivi.php" >Scrivi</span></div>
        <div class="tasto_nav"><a href="login.php" >Login</a></div>
        <div class="tasto_nav"><a href="scopri.php">Scopri</a></div>
        <div class="tasto_nav"><span href="logout.php">Logout</span></div>';
    }
    ?>
</nav>
