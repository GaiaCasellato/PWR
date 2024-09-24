<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?> 

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Gaia Casellato">
    <meta name="keywords" lang="it" content="html">
    <meta name="description" content="Sito web POST-IT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="logo2.ico" type="image/x-icon">
    <title>Home - Social Network</title>
</head>
<body>
    <!-- L'header conterrà il tweet recente -->
    <header> 
        <?php include 'tweet_recente.php' ?>
    </header>
        <?php echo"<div class='err'>".$_SESSION['url_access_denied']."</div>"; 
        $_SESSION['url_access_denied'] = ""; ?>
        <main> 
            <!-- Divisione della pagine in due sezioni principali: una di sinistra dove ci sarà 
                 il contenuto di ogni pagina e sarà differente, una di destra dove sarà presente il menù di navigazione-->
            <div class="left-column">
            <div class="titolo">  
            <h1>Benvenuto su POST-IT</h1> 
            </div>
            <div class="signup-container">
            <img src="logo.jpg" class="logo" alt="Logo POST-IT">
                <p>Libera i tuoi pensieri e scopri quelli degli altri.</p>
                <p> Sei nuovo qui? </p>
                <button class="btn" onclick="location.href='registrazione.php'">Registrati ora!</button>
                
                <p> Hai già un account? Accedi adesso! </p>
                <button class="btn" onclick="location.href='login.php'">Accedi</button>
            </div>  <!-- qui si chiude signup-container -->
            </div> <!-- qui si chiude left-column-->
            <div class="right-column">
            <?php include "navigazione.php" ?> 
            </div>
        </main> 
        <!-- Piccola descrizione di cosa si può fare in questo sito-->
        <h3>Cosa puoi fare?</h3>
        <ul class="dropdown-menu">
            <li>Gestisci il tuo profilo con un click attraverso le pagine 'Registrazione' e 'Login'!</li>
            <li>Condividi i tuoi pensieri nella sezione 'Scrivi'!</li>
            <li>Sei curioso di sapere cosa stanno condivendendo gli altri utenti? Vai alla sezione 'Scopri'!</li>
        </ul>
    <!-- Footer contenente le informazioni dell'autore e la pagina corrente -->
    <footer>
        <?php include "footer.php" ?>
    </footer>
 </body> 
</html>

