<?php
session_start(); // Démarrer la session

$host = 'localhost';
$dbname = 'LocationChef';
$username = 'root';
$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    // Rediriger vers la page de connexion
    header("Location: ../connexion.html");
    exit;
}

// recup l'ID du client depuis la session
$client_id = $_SESSION['client_id'];

// recup les données du formulaire
$chef_id = $_POST['chef_id'] ?? null;
$date_reservation = $_POST['date_reservation'] ?? null;
$heure_reservation = $_POST['heure_reservation'] ?? null;
$nombre_heures = $_POST['nombre_heures'] ?? 1; 

if (!$chef_id || !$date_reservation || !$heure_reservation) {
    die('Tous les champs sont requis.');
}

// combine la date et l'heure
$date_time_reservation = $date_reservation . ' ' . $heure_reservation;

// recup le tarif horaire du chef
$stmt = $pdo->prepare("SELECT tarif_horaire FROM Chefs WHERE id = :chef_id");
$stmt->execute([':chef_id' => $chef_id]);
$chef = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chef) {
    die('Chef introuvable.');
}

$tarif_horaire = $chef['tarif_horaire'];

$prix_total = $tarif_horaire * $nombre_heures;

// insert la réservation dans la base de données
try {
    $stmt = $pdo->prepare("
        INSERT INTO Reservations (client_id, chef_id, date_reservation, nombre_heures, statut, prix_total)
        VALUES (:client_id, :chef_id, :date_reservation, :nombre_heures, 'En attente', :prix_total)
    ");
    $stmt->execute([
        ':client_id' => $client_id,
        ':chef_id' => $chef_id,
        ':date_reservation' => $date_time_reservation,
        ':nombre_heures' => $nombre_heures,
        ':prix_total' => $prix_total
    ]);

    // redirige vers la page de confirmation
    header("Location: ../confirm.html");
    exit;
} catch (Exception $e) {
    die('Erreur lors de l\'enregistrement de la réservation : ' . $e->getMessage());
}
?>