<?
$servername = "localhost";
$username = "normale";
$password = "posso_leggere?";
$dbname = "social_network";

// Crea la connessione per l'utente normale
$conn = mysqli_connect($servername, $username, $password, $dbname);

mysqli_set_charset($conn,"utf8mb4");

// Controllo la connessione
if (mysqli_connect_errno()) {
    printf ("<p>errore - collegamento al DB impossibile: %s</p>\n", mysqli_connect_error());
}

?>
