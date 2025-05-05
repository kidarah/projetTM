<?php
session_start();
echo "Session active - ID utilisateur : " . ($_SESSION['user_id'] ?? "Aucune session active.");
?>
