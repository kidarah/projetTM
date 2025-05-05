<?php
session_start();
session_destroy(); // Supprime la session
header("Location: ../connexion.html"); // Redirige vers la connexion
exit();
?>
