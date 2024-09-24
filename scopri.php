<?php
// Avvia la sessione
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo $_SESSION['error_message'];
$_SESSION['error_message']=""; 
// Include il file di configurazione per la connessione al database
include 'config_normale.php';


// Recupera tutti i tweet dal database
$sql = "SELECT * FROM `tweets`
        ORDER BY tweets.data DESC";
$result = mysqli_query($conn, $sql);

// Controlla se ci sono risultati
$tweets = [];
if (mysqli_num_rows($result) > 0) {
    while( $row = mysqli_fetch_assoc($result)) {
        $tweet = array(
            'username' => $row['username'],
            'data' => $row['data'],
            'testo' => $row['testo']
        );
        $tweets[] = $row;
    }
}
mysqli_close( $conn );
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Gaia Casellato">
    <meta name="keywords" lang="it" content="html">
    <meta name="description" content="Pagina esplora per POST-IT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="logo2.ico" type="Logo">
    <title>POST-IT - Scopri</title>
</head>
<body>
    <header>
    <?php include 'tweet_recente.php'; ?>
        <img src="logo.jpg" class = "logo_pagine" alt="Logo POST-IT">
        <div>
            <h1>POST-IT</h1>
        </div>
    </header>
    <div> 
    <?php
        // Mostra il messaggio di errore se esiste
        if (isset($_SESSION['error_message'])) {
            echo '<div class="err">' . $_SESSION['error_message'] . '</div>';
            // Rimuovi il messaggio di errore dalla sessione dopo averlo visualizzato
            unset($_SESSION['error_message']);
        }
    ?>
    </div>
<main>
<div class="left-column">
<div class="tweet-container">
            <!--Visualizzazione di tutti i tweet contenuti nel database in un elenco-->
            <h3>Tutti i POST-IT</h3>
            <!-- Verifica se ci sono tweet-->
            <?php 
                if (count($tweets) > 0){
                    foreach ($tweets as $tweet){
                            echo "<div class=\"tweet\">";
                            echo "<div class=\"header\">";
                            echo "<span class=\"author\">".$tweet['username']."</span>";
                            echo "<span class=\"date\">".$tweet['data']."</span>";
                            echo "</div>";
                            echo "<p class=\"content\">".$tweet['testo']."</p>";
                            echo "</div>";
                    }
                } else {
                    echo "<p class=\"no-tweets\">Non ci sono tweet da visualizzare!</p>";
                }
            ?>
    
    </div>
</div> <!--Chiude la left-column -->
<div class="right-column">
    <?php include "navigazione.php" ?> 
</div>    
</main>
    <footer>
        <?php include "footer.php" ?>
    </footer>
</body>
</html>
