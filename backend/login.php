<?php
session_start();

// Fonction de connexion à la base de données
function connectToDatabase() {
    $host = 'localhost';
    $user = 'u716777407_admin1';
    $password = 'Dalulou123'; 
    $database = 'u716777407_LocationChef';

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Erreur de connexion : " . mysqli_connect_error());
    }

    return $conn;
}

// Connexion à la base de données
$conn = connectToDatabase();

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, role, mot_de_passe FROM Utilisateurs WHERE email = ?");
    if (!$stmt) {
        die("Erreur de préparation : " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../FrontView.html");
        exit();
    } else {
        header("Location: connexion.html"); // Échec → retour à la page de connexion
        exit();
    }
}
?>
