<?php
// Avvia la sessione
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Controlla se l'utente è autenticato
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['error_message'] = "Identità non verificata! Non hai permesso di usare questa funzionalità senza autenticazione.";
    header("Location: scopri.php");
    exit();
}

// Includi il file di configurazione per la connessione al database
include 'config_privilegiato.php';

// Verifica se il modulo è stato inviato
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Controlla che il contenuto non sia vuoto
    if (isset($_REQUEST['tweet']) && $_REQUEST['tweet']) {
        // Recupera il contenuto del tweet
        $content = trim($_REQUEST['tweet']);
    
        // Recupera l'ID dell'utente dalla sessione
        $username = $_SESSION['username'];

        //forzo la codifica dei dati del database
        mysqli_set_charset($conn, "utf8mb4");

        // Prepara e esegui la query SQL per inserire il tweet nel database
        $sql = "INSERT INTO tweets (username, data, testo) VALUES (?, NOW(), ?)";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "ss", $username, $content);

        if (mysqli_stmt_execute($statement)) {
            $_SESSION['success_message'] = "Tweet inviato con successo!";
        } else {
            $_SESSION['error_message'] = "Errore nell'invio del tweet!";
        }
        mysqli_stmt_close( $statement);
        mysqli_close( $conn );
        header("Location: bacheca.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Il tweet non può essere vuoto!";
        mysqli_stmt_close( $statement);
        mysqli_close( $conn );
        header("Location: scrivi.php");
        exit();
    }
}
?>

<!DOCTYPE html>

<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Gaia Casellato">
    <meta name="keywords" lang="it" content="html">
    <meta name="description" content="Pagina per scrivere un tweet per POST-IT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo2.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title> POST-IT - Scrivi</title>
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
    ?>
    </div>
<main><div class="left-column">
        <div class="form-container">
            <h3>Scrivi un POST-IT!</h3>
            <?php
                if(isset($_SESSION['success_message'])) {
                    echo '<div class="success">' . $_SESSION['success_message'] . '</div>';
                    // Rimuove il messaggio di errore dalla sessione dopo averlo visualizzato
                    unset($_SESSION['success_message']);
                }
            ?>
            <!--Form per la scrittura di un tweet composto da una textarea in cui inserire il testo del messaggio e un bottone per il submit-->
           
            <form id="scrivi" action="scrivi.php" method="post">
                <label for="tweet">Testo (max 140 caratteri):</label>
                <textarea id="tweet" name="tweet" rows="4" maxlength="140" required></textarea>
                <button id="btn_invia" type="submit" class="btn">Invia</button>
            </form>
    

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
