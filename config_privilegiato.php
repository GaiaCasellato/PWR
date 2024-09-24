<?php
$servername = "localhost";
$usernamedb = "privilegiato";
$password = "SuperPippo!!!";
$dbname = "social_network";

// Creo la connessione per l'utente privilegiato
$conn = mysqli_connect($servername, $usernamedb, $password, $dbname);

mysqli_set_charset($conn,"utf8mb4");
// Controllo la connessione
if (mysqli_connect_errno()) {
    printf ("<p>errore - collegamento al DB impossibile: %s</p>\n", mysqli_connect_error());
}
?>
