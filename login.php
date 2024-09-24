<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se l'utente è già loggato, reindirizza alla pagina home.php
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $_SESSION['url_access_denied'] = "Sei già loggato."; // crea variabile sessione pagina inaccessibile
    header("Location: home.php");
    exit();
}

include 'config_normale.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_REQUEST['user']) && isset($_REQUEST['pwd'])){
        $username = trim($_REQUEST['user']);
        $password = trim($_REQUEST['pwd']);
    
        $sql = "SELECT * FROM utenti WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($password === $row['pwd']) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;

                // Memorizza l'username in un cookie per 16 ore
                setcookie('last_username', $username, time() + 16 * 3600, '/');

                $_SESSION['success_message'] = 'Accesso effettuato con successo!';
                mysqli_close( $conn );
                header('Location: bacheca.php');
                exit();
            }else { //compilo form con username esistente ma pwd sbagliata, errore password errata
                $_SESSION['error_message'] = 'Password errata! Si prega di riprovare.';
                mysqli_close( $conn );
                header('Location: login.php');
                exit();
            }
        } else { //compilo form con utente inesistente, non guardo la pwd
            $_SESSION['error_message'] = 'Username non trovato! Si prega di registrarsi.';
            mysqli_close( $conn );
            header('Location: registrazione.php');
            exit();
        }
    }
    mysqli_close( $conn );
}


?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Gaia Casellato">
    <meta name="keywords" lang="it" content="html">
    <meta name="description" content="Pagina di login per POST-IT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="login.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="logo2.ico" type="Logo">
    <title>Login - POST-IT </title>
</head>
<body>
<?php include 'tweet_recente.php'; ?>
    <header>
    <img src="logo.jpg" class = "logo_pagine" alt="Logo POST-IT">
        <h1>Login</h1>
    </header> 
    <?php
        // Mostra il messaggio di errore se esiste
        if (isset($_SESSION['error_message'])) {
            echo '<div class="err">' . $_SESSION['error_message'] . '</div>';
            // Rimuovi il messaggio di errore dalla sessione dopo averlo visualizzato
            unset($_SESSION['error_message']);
        }
        if(isset($_SESSION['success_message'])) {
            echo '<div class="success">'.$_SESSION['success_message'].'</div>';
            // Rimuovi il messaggio di errore dalla sessione dopo averlo visualizzato
            unset($_SESSION['success_message']);
        }
?>
    <main>
        <div class="left-column">
        <!--Form per la procedura di autenticazione, due textfield relativi a username e password, bottone invia per fare il submit,
        bottone cancella per pulire i campi di testo e bottone per continuare senza autenticarsi con reindirizzameno diretto alla pagina scopri-->
        <div class="container">
            <h2>Inserisci i tuoi dati</h2>
           
            <form id="login" action="login.php" method="post">
            <div class="form-group">
                <label for="user">Username:</label>
                <input type="text" id="user" name="user" value="<?php echo isset($_COOKIE['last_username']) ?$_COOKIE['last_username'] : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" id="pwd" name="pwd" required>
            </div>
                <button id="invia" type="submit" class="btn">Invia</button>
                <button id="cancella" type="button" class="btn" onclick="clearFields()">Cancella</button>
            
            <button id="btn_autenticazione" type="button" class="btn" onclick="location.href='scopri.php'">Continua senza autenticarsi</button>
            </form>
        </div>
    </div> <!-- qui chiudo la left-column-->
    <div class="right-column">
        <?php include "navigazione.php" ?> 
    </div>
    </main>
<footer>
    <?php include "footer.php" ?>
</footer>
</body>
<script>
    "use strict";
    function clearFields(){
        document.getElementById("user").value = "";
        document.getElementById("pwd").value = "";
    }
</script>
</html>


