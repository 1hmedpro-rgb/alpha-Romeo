<?php
// On vérifie que le formulaire a bien été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. On récupère les données du formulaire
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // 2. On prépare l'email
    $destinataire = "votre.email@efrei.net"; // Remplacez par votre mail
    $sujet = "Nouveau message de : " . $nom;
    $contenu = "Nom: $nom\nEmail: $email\nMessage:\n$message";
    $entetes = "From: " . $email;

    // 3. On envoie l'email
    if (mail($destinataire, $sujet, $contenu, $entetes)) {
        // Si ça marche, on affiche un message
        echo "<h1>Super ! Votre message a bien été envoyé.</h1>";
        echo "<a href='../html/Ecole.html'>Retour à l'accueil</a>";
    } else {
        // S'il y a une erreur technique
        echo "<h1>Oups, une erreur est survenue lors de l'envoi.</h1>";
    }
} else {
    // Si quelqu'un essaie d'ouvrir ce fichier directement sans passer par le formulaire
    echo "Accès refusé.";
}
?>

<?php
// 1. On récupère directement les données brutes (Sans sécurité)
$nom = $_POST["nom"];
$email = $_POST["email"];
$message = $_POST["message"];

// 2. On prépare l'email
$destinataire = "votre.email@efrei.net"; // Remplacez par votre mail
$sujet = "Nouveau message de : " . $nom;
$contenu = "Nom: $nom\nEmail: $email\nMessage:\n$message";
$entetes = "From: " . $email;

// 3. On envoie l'email
if (mail($destinataire, $sujet, $contenu, $entetes)) {
        // Si ça marche, on affiche un message
        echo "<h1>Super ! Votre message a bien été envoyé.</h1>";
        echo "<a href='../html/Ecole.html'>Retour à l'accueil</a>";
    } else {
        // S'il y a une erreur technique
        echo "<h1>Oups, une erreur est survenue lors de l'envoi.</h1>";
    }