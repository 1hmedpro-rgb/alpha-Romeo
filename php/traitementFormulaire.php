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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Envoie-Formulaire-Efrei</title>
    
        <link rel="stylesheet" href="../css/Ecole.css">
        <link rel="stylesheet" href="../css/traitementFormulaire.css">
        <link rel="stylesheet" href="../css/headerfooter.css">

    </head>

    <body>
        <div id="haut-de-page">
            <header>
                <nav> 
                    <a href ="../html/Ecole.html"> <img src="../image/logo_efrei.png" alt="logo_efrei" id="logo"></a>
                    <a href="../html/Ecole.html" class="lien">L'ECOLE</a>
                    <a href="../html/cours_et_formations.html" class="lien">COURS / FORMATIONS</a>
                    <a href="../html/equipe_enseignante.html" class="lien">EQUIPE ENSEIGNANTE</a>
                    <a href="../html/contactez_nous.html" class="lien">CONTACTEZ-NOUS</a>
                    <a href="../html/a_propos.html" class="lien">A PROPOS</a>
                </nav>
            </header>
        </div>

       
        <div class="boite-validation">
            <?php 
                $civilite = $_POST['civilite']; 
                $prenom = $_POST['prenom'];
                $nom = $_POST['nom'];
                $autres_prenoms = $_POST['autres_prenoms'];
                $email = $_POST['email'];

                echo "<h1>Merci pour votre candidature !</h1>";
                echo "<p>Bonjour $civilite $prenom $nom, nous avons bien reçu votre demande.</p>";
                echo "<p>Email de contact : <strong>$email</strong></p>";
            ?>
        </div>

        <footer>    
            <div class ="divs">
                <h3 id="div1">NAVIGATION</h3><br><br>
                <a href="../html/Ecole.html" class="lien_footer">L'ECOLE</a><br><br>
                <a href="../html/cours_et_formations.html" class="lien_footer">COURS / FORMATIONS</a>
            </div>
            
            <div class ="divs">
                <h3 id="div2">LIENS RAPIDES</h3><br><br>
                <a href="../html/equipe_enseignante.html" class="lien_footer">EQUIPE ENSEIGNANTE</a><br><br>
                <a href="../html/a_propos.html" class="lien_footer">A PROPOS</a>
            </div>
            
            <div class ="divs"> 
                <h3 id="div3">CONTACT</h3><br><br>
                <a href="../html/contactez_nous.html" class="lien_footer">CONTACTEZ-NOUS</a>
            </div>
        </footer>
    </body>
</html>