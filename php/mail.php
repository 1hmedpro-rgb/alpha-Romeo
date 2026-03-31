<?php
// 1. On récupère directement les données brutes
$nom = $_POST["nom"];
$email = $_POST["email"];
$message = $_POST["message"];

// 2. On prépare l'email
$destinataire = "votre.email@efrei.net";
$sujet = "Nouveau message de : " . $nom;
$contenu = "Nom: $nom\nEmail: $email\nMessage:\n$message";
$entetes = "From: " . $email;

// 3. CORRECTION : On envoie l'email ET on stocke le résultat dans la variable
$envoi_reussi = mail($destinataire, $sujet, $contenu, $entetes);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statut de l'envoi</title>
    <link rel="stylesheet" href="../css/headerfooter.css">
    <link rel="stylesheet" href="../css/mail.css">
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

    <main class="centre-page">
        <?php if ($envoi_reussi): ?>
            <div class="boite-message">
                <h1>Super !</h1>
                <p>Merci <strong><?php echo $nom; ?></strong>. Votre message a bien été envoyé, nous vous répondrons très vite à l'adresse <strong><?php echo $email; ?></strong>.</p>
                <a href="../html/Ecole.html" class="btn-retour">Retour à l'accueil</a>
            </div>
        <?php else: ?>
            <div class="boite-message erreur">
                <h1>Oups !</h1>
                <p>Désolé <?php echo $nom; ?>, une erreur technique est survenue. Votre message n'a pas pu être envoyé.</p>
                <a href="../html/Ecole.html" class="btn-retour">Retour à l'accueil</a>
            </div>
        <?php endif; ?>
    </main>

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