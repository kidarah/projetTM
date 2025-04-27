<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère et sécurise les données du formulaire
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Variables pour détecter des erreurs
    $erreurs = [];

    // Vérifier que le nom contient seulement des lettres, espaces ou tirets
    if (empty($nom) || !preg_match("/^[a-zA-ZÀ-ÿ '-]+$/u", $nom)) {
        $erreurs[] = "Le nom est invalide.";
    }

    // Vérifier l'email avec filtre PHP
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse email est invalide.";
    }

    // Vérifier que le message n'est pas vide et fait au moins 10 caractères
    if (empty($message) || strlen($message) < 1) {
        $erreurs[] = "Le message doit contenir au moins 10 caractères.";
    }

    if (empty($erreurs)) {
        // Si tout est valide, envoyer le mail
        $destinataire = "yumchefcontact@gmail.com"; 
        $sujet = "Nouveau message de $nom via le formulaire de contact";

        $contenu = "Nom: $nom\n";
        $contenu .= "Email: $email\n\n";
        $contenu .= "Message:\n$message";

        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($destinataire, $sujet, $contenu, $headers)) {
            echo "<p>Message envoyé avec succès. Merci $nom !</p>";
            echo "<a href='/Contact.html'>Retour</a>";
        } else {
            echo "<p>Erreur : le message n'a pas pu être envoyé.</p>";
            echo "<a href='/Contact.html'>Retour</a>";
        }
    } else {
        // Si erreurs, les afficher
        echo "<p>Erreur lors de l'envoi :</p>";
        echo "<ul>";
        foreach ($erreurs as $erreur) {
            echo "<li>" . htmlspecialchars($erreur) . "</li>";
        }
        echo "</ul>";
        echo "<a href='Contact.html'>Retour</a>";
    }
}
?>
