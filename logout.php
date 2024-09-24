<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['error_message'] = "Identità non verificata! Non hai permesso di usare questa funzionalità senza autenticazione.";
    header("Location: login.php");
    exit();
}

session_unset();

// Distrugge la sessione
session_destroy();

// Reindirizza alla pagina home.php dopo il logout
header('Location: home.php'); // vedi come usare bene header
exit();
?>
