<?php
// 1. On récupère directement toutes les données tapées par l'utilisateur
$civilite = $_POST["civilite"];
$prenom = $_POST["prenom"];
$nom = $_POST["nom"];
$autres_prenoms = $_POST["autres_prenoms"];
$email = $_POST["email"];
$nationalite = $_POST["nationalite"];
$pays = $_POST["pays"];
$indicatif = $_POST["indicatif"];
$telephone = $_POST["telephone"];

/* 2 Sauvegarde CV */
$nom_fichier = $_FILES['cv']['name'];
move_uploaded_file($_FILES['cv']['tmp_name'], '../CV/' . $nom_fichier);

// 3. Préparation de l'email
$destinataire = "ahmed.muhammad@efrei.net"; 
$sujet = "Nouvelle candidature de : " . $prenom . " " . $nom;

$contenu = "Candidature reçue :\n\n";
$contenu .= "Nom complet : $civilite $prenom $nom\n";
$contenu .= "Autres prénoms : $autres_prenoms\n";
$contenu .= "Email : $email\n";
$contenu .= "Téléphone : $indicatif $telephone\n";
$contenu .= "Nationalité : $nationalite\n";
$contenu .= "Pays : $pays\n";
$contenu .= "\nLe CV a bien été enregistré dans votre dossier 'uploads' sous le nom : $nom_fichier";

$entetes = "From: " . $email;

// 4. On envoie l'email
mail($destinataire, $sujet, $contenu, $entetes);

// 5. On affiche un message de réussite à l'écran
echo "<h1>Merci $prenom !</h1>";
echo "<p>Votre candidature et votre CV ont bien été envoyés.</p>";
echo "<a href='../html/Ecole.html'>Retour à l'accueil</a>";
?>