<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo $_SESSION['error_message'];
$_SESSION['error_message']="";
// inizializzazione delle variabili per il controllo in php
$nameError = $surnameError = $birthdateError = $addressError = $usernameError = $pwdError = "";
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {  //tentativo di registrazione, arrivo qui dopo il controllo piu rapido del js

    $namec = trim($_POST['name']);
    $surnamec = trim($_POST['surname']);
    $birthdatec = trim($_POST['birthdate']);
    $addressc = trim($_POST['address']);
    $usernamec = trim($_POST['username']);
    $pwdc = trim($_POST['pwd']);
    //variabili di controllo 

    // connessione privilegiata
    include "config_privilegiato.php";
    $stmt = $conn->prepare("SELECT * FROM utenti WHERE username = ?");
    $stmt->bind_param("s", $usernamec);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) { //ho trovato un utente con il nome: suggerisco di cambiarlo prima ancora di controllare gli altri dati, l'ordine è indifferente: devono essere rispettate entrambe le condizioni
        $error_message = "<div class='error-message'>Inserisci un altro username: $usernamec è già in uso</div>"; //rimando quello che ha restituito risultati
    }
    else{
        $isValid = true;

        if (!preg_match('/^[A-Z][a-zA-Z ]{1,11}$/', $namec)) {
            $nameError = "Nome: deve iniziare con una lettera maiuscola, da 2 a 12 caratteri";
            $isValid = false;
        }
        if (!preg_match('/^[A-Z][a-zA-Z ]{1,15}$/', $surnamec)) {
            $surnameError = "Cognome: deve iniziare con una lettera maiuscola, da 2 a 16 caratteri";
            $isValid = false;
        }
        if (!preg_match('/^(?:\d{4})-(?:0?[1-9]|1[0-2])-(?:0?[1-9]|[12][0-9]|3[01])$/', $birthdatec)) {
            $birthdateError = "Data di Nascita: deve essere nella forma aaaa-mm-gg e rispettare il formato della data";
            $isValid  = false;
        }
        if (!preg_match('/^(Via|Corso|Largo|Piazza|Vicolo) [a-zA-Z ]+ \d{1,4}$/', $addressc)) {
            $addressError = "Indirizzo: deve essere nella forma Via/Corso/Largo/Piazza/Vicolo nome numeroCivico";
            $isValid = false;
        }
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_-]{3,9}$/', $usernamec)) {
            $usernameError = "Username: deve essere da 4 a 10 caratteri, iniziare con una lettera";
            $isValid = false;
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d.*\d)(?=.*[#!?@%^&*+=].*[#!?@%^&*+=]).{8,16}$/', $pwdc)){
            $pwdError = "Password: deve essere da 8 a 16 caratteri, almeno 1 lettera maiuscola, 1 lettera minuscola, 2 numeri e 2 caratteri speciali tra i seguenti #!?@%^&*+=";
            $isValid = false;
        }
        include "config_privilegiato.php";

        if ($isValid) { 
            $stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, data, indirizzo, username, pwd) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $namec, $surnamec, $birthdatec, $addressc, $usernamec, $pwdc);
            $stmt->execute();

            if ($stmt->affected_rows > 0) { //registro l'utente e salvo il login e proseguo con l'utente autenticato
                $_SESSION['username'] = $usernamec;
                $_SESSION['logged_in'] = true;
                setcookie('last_username', $usernamec, time() + (3600 * 16), "/");
                header("Location: bacheca.php");
                exit();
            } else {
                $error_message = "<div class='error-message'>Errore nell'inserimento nel database</div>";
            }
        }
    }
    
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Gaia Casellato">
    <meta name="keywords" lang="it" content="html">
    <meta name="description" content="Registrazione POST-IT">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="logo2.ico" type="Logo">
    <title>Registrazione - Social Network</title>
</head>
<body>
    <header>
    <?php include 'tweet_recente.php'; ?>
    <img src="logo.jpg" class = "logo_pagine" alt="Logo POST-IT">
        <h1>Registrazione</h1>
    </header>
    <?php
     if (isset($_SESSION['error_message'])) {
        echo '<div class="err">' . $_SESSION['error_message'] . '</div>';
        // Rimuovi il messaggio di errore dalla sessione dopo averlo visualizzato
        unset($_SESSION['error_message']);
    }
        ?>
    <main> 
    <div class="left-column">
    <div class="container">
        <h2>Compila il modulo per registrarti</h2>
        <form id="form_registrazione" method="POST">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" value="<?php echo $namec ?>" required>
                <span class="error-message" id="nameError"><?php echo $nameError?></span> <!-- sono riempite in caso di errore trovato --->
            </div>
            <div class="form-group">
            <label for="surname">Cognome:</label>
                <input type="text" id="surname" name="surname" value="<?php echo $surnamec ?>"required>
                <span class="error-message" id="surnameError"><?php echo $surnameError?></span>
            </div>
            <div class="form-group">
            <label for="birthdate">Data di Nascita:</label>
                <input type="text" id="birthdate" name="birthdate" value="<?php echo $birthdatec ?>"required>
                <span class="error-message" id="birthdateError"><?php echo $birthdateError?></span>
            </div>
            <div class="form-group">
            <label for="address">Indirizzo:</label>
                <input type="text" id="address" name="address" value="<?php echo $addressc ?>"required>
                <span class="error-message" id="addressError"><?php echo $addressError?></span>
            </div>
            <div class="form-group">
            <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $usernamec ?>"required >
                <span class="error-message" id="usernameError"><?php echo $usernameError?></span>
            </div>
            <div class="form-group">
            <label for="pwd">Password:</label>
                <input type="password" id="pwd" name="pwd" value="<?php echo $pwdc?>"required>
                <span class="error-message" id="pwdError"><?php echo $pwdError?></span>
            </div>
            
            <button class="btn" type="submit" id="registrati">Registrati</button>
            </div> <!-- qui si chiude il div di container -->
        </form>
        </div> <!-- qui si chiude il div di left-column -->
        <div class="right-column">
        <?php   
            include "navigazione.php"?>
        </div>
        </main> 


<footer>
    <?php include "footer.php" ?>
</footer>
</body> 
<script>
    "use strict";
    document.getElementById("form_registrazione").addEventListener("submit", function(event) {
    if (!validateForm()) {
        event.preventDefault(); // Previene l'invio del modulo se la validazione fallisce
    } //altrimenti mando al php che inserisce nel database
});

function validateForm() {
    var isValid = true;

    var namePattern = /^[A-Z][a-zA-Z ]{1,11}$/;
    var surnamePattern = /^[A-Z][a-zA-Z ]{1,15}$/;
    var birthdatePattern = /^(?:\d{4})-(?:0?[1-9]|1[0-2])-(?:0?[1-9]|[12][0-9]|3[01])$/;
    var addressPattern = /^(Via|Corso|Largo|Piazza|Vicolo) [a-zA-Z ]+ \d{1,4}$/;
    var usernamePattern = /^[a-zA-Z][a-zA-Z0-9_-]{3,9}$/;
    var pwdPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d.*\d)(?=.*[#!?@%^&*+=].*[#!?@%^&*+=]).{8,16}$/;

    var name = document.getElementById("name").value;
    var surname = document.getElementById("surname").value;
    var birthdate = document.getElementById("birthdate").value;
    var address = document.getElementById("address").value;
    var username = document.getElementById("username").value;
    var pwd = document.getElementById("pwd").value;

    var errorIds = ["nameError", "surnameError", "birthdateError", "addressError", "usernameError", "pwdError"];
    errorIds.forEach(function(id) { //itero su tutti i campi errore del form
        var errorSpan = document.getElementById(id); //prendo l'errore se in precedenza è stato segnalato
        if (errorSpan) {
            errorSpan.textContent = "";  //per il prossimo controllo resetto tutto
        }
    });
    //non tolgo quelle che passano gli errori, ma ogni volta cancello tutte quelle esistenti 
    //e le ricompilo nel caso di ritrovamento o meno dello stesso errore (o di altri eventuali nel caso di modifica di altri campi)
    //questo permette di controllare anche i campi segnalati corretti alla pressione precedente:
    //l'utente può così modificare anche in caso di regex compatibili, ritrovando un errore su una compilazione successiva

    if (!namePattern.test(name)) {
        document.getElementById("nameError").textContent = "Nome: deve iniziare con una lettera maiuscola, da 2 a 12 caratteri.";
        isValid = false;
    }

    if (!surnamePattern.test(surname)) {
        document.getElementById("surnameError").textContent = "Cognome: deve iniziare con una lettera maiuscola, da 2 a 16 caratteri.";
        isValid = false;
    }

    if (!birthdatePattern.test(birthdate)) { 
        document.getElementById("birthdateError").textContent = "Data di Nascita: deve essere nella forma aaaa-mm-gg e rispettare il formato della data.";
        isValid = false;
    }

    if (!addressPattern.test(address)) {
        document.getElementById("addressError").textContent = "Indirizzo: deve essere nella forma Via/Corso/Largo/Piazza/Vicolo nome numeroCivico.";
        isValid = false;
    }

    if (!usernamePattern.test(username)) {
        document.getElementById("usernameError").textContent = "Username: deve essere da 4 a 10 caratteri, deve iniziare con una lettera.";
        isValid = false;
    }

    if (!pwdPattern.test(pwd)) {
        document.getElementById("pwdError").textContent = "Password: deve essere da 8 a 16 caratteri, almeno 1 lettera maiuscola, 1 lettera minuscola, 2 numeri e 2 caratteri speciali tra i seguenti #!?@%^&*+=";
        isValid = false;
    }

    return isValid;
}
</script>
</html>

