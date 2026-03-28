<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Envoie-Formulaire-Efrei</title>
    
        <link rel="stylesheet" href="../css/Ecole.css">
        <link rel="stylesheet" href="../css/traitementFormulaire.css">
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