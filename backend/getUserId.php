<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit;
}

echo json_encode(["user_id" => $_SESSION['user_id']]);
?>
