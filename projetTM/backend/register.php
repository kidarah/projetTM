<?php
header('Content-Type: application/json');
// Initialiser un tableau pour stocker les erreurs
$errors = [];

// Vérifier le type d'utilisateur (Chef ou Client)
if (!isset($_POST['userType']) || ($_POST['userType'] !== 'chef' && $_POST['userType'] !== 'client')) {
    $errors['userTypeErrors'] = 'Veuillez sélectionner un type d’utilisateur (Chef ou Client).';
}

// Vérifier le prénom
if (empty($_POST['firstname'])) {
    $errors['firstnameErrors'] = 'Le prénom est requis.';
}

// Vérifier le nom de famille
if (empty($_POST['lastname'])) {
    $errors['lastnameErrors'] = 'Le nom de famille est requis.';
}

// Vérifier l'email
if (empty($_POST['email'])) {
    $errors['emailErrors'] = 'L’adresse email est requise.';
} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['emailErrors'] = 'Veuillez entrer une adresse email valide.';
}

// Vérifier le mot de passe
if (empty($_POST['password'])) {
    $errors['passwordErrors'] = 'Le mot de passe est requis.';
} elseif (strlen($_POST['password']) < 6) {
    $errors['passwordErrors'] = 'Le mot de passe doit contenir au moins 6 caractères.';
}

// Vérifier la confirmation du mot de passe
if (empty($_POST['confirm_password'])) {
    $errors['confirm_passwordErrors'] = 'La confirmation du mot de passe est requise.';
} elseif ($_POST['password'] !== $_POST['confirm_password']) {
    $errors['confirm_passwordErrors'] = 'Les mots de passe ne correspondent pas.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
} else {
    echo json_encode(['success' => true, 'message' => 'Le formulaire a été soumis avec succès !']);
    exit;
}


?>



