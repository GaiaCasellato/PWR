<?php
// Avvia la sessione
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['error_message'] = "Identità non verificata! Non hai permesso di usare questa funzionalità senza autenticazione.";
    header("Location: login.php");
    exit();
}

// Include il file di configurazione per la connessione al database
include 'config_normale.php';

// Ottiene l'ID dell'utente 
$username = $_SESSION['username'];

// Imposta le variabili per il filtro temporale
if(isset($_POST['start_date']) && isset($_POST['end_date'])){
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    // Controlla che la data di fine non sia prima della data di inizio
    if ($start_date > $end_date) {
        $_SESSION['error_message'] = "La data di fine non può essere prima della data di inizio!";
        header("Location: bacheca.php");
        exit();
    }
}else{
    $start_date = null;
    $end_date = null;
}

// Crea la query SQL per recuperare i tweet dell'utente
$sql = "SELECT * FROM tweets WHERE username = ? 
        ORDER BY tweets.data DESC";


if ($start_date && $end_date) {
    $sql = "SELECT * FROM tweets WHERE username = ? AND tweets.data BETWEEN ? AND ?  ORDER BY tweets.data DESC ";
    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, "sss",$username , $start_date, $end_date);
} else {
    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, "s", $username);
}


mysqli_set_charset($conn, "utf8mb4");

// Esegue la query
mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result( $statement );
$tweets = [];
// Estrae i risultati
while ($row = mysqli_fetch_assoc($result)) {
    $tweets[] = $row;
}
mysqli_stmt_close($statement);
mysqli_close( $conn );
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Gaia Casellato">
    <meta name="keywords" lang="it" content="html">
    <meta name="description" content="Pagina bacheca per POST-IT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" href="logo2.ico" type="Logo">
    <title>POST-IT - Bacheca</title>
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
            // Rimuove il messaggio di errore dalla sessione dopo averlo visualizzato
            unset($_SESSION['error_message']);
        }
        if(isset($_SESSION['success_message'])) {
            echo '<div class="success">' . $_SESSION['success_message'] . '</div>';
            // Rimuove il messaggio di errore dalla sessione dopo averlo visualizzato
            unset($_SESSION['success_message']);
        }
    ?>
    </div>

<main>
    <div class="left-column">
        <div class="tweet-container">
            <h3>Bacheca</h3>
            <!--Filtro temporale composto da un form per inserire due date, una di inizio e una di fine, e dal bottone filtra per il submit-->
            <form id="filtro" method="POST" action="bacheca.php">
                <div class="form-group">
                <label class="data_filtro" for="start_date">Data Inizio:</label>
                <input type="datetime-local" id="start_date" name="start_date" value="" required>
                <label class="data_filtro" for="end_date"> Data Fine:</label>
                <input type="datetime-local" id="end_date" name="end_date" value="" required>
                <button id="filtra" type="submit" class="btn">Filtra</button>
                </div>
            </form>
            <?php 
            if ((count($tweets) == 0) && ($start_date== null) && ($end_date == null)) {
                echo "<p class=\"no-tweets\">Non hai ancora scritto un POST-IT...Vai alla sezione scrivi e creane uno ora!";
            } elseif (count($tweets) > 0){
                    foreach ($tweets as $tweet){
                            echo "<div class=\"tweet\">";
                            echo "<div class=\"header\">";
                            echo "<span class=\"author\">".$tweet['username']."</span>";
                            echo "<span class=\"date\">".$tweet['data']."</span>";
                            echo "</div>";
                            echo "<p class=\"content\">".$tweet['testo']."</p>";
                            echo "</div>";
                    }
                } 
                else {
                    echo "<p class=\"no-tweets\">Non ci sono POST-IT nelle date selezionate!</p>";
                }
                
            ?>
            </div>
        </div>
        <div class="right-column">
        <?php include "navigazione.php" ?>
        </div>
    </main>
    <footer>
        <?php include "footer.php" ?>
    </footer>
</body>
</html>
