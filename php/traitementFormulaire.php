

<?php 
    
    $genre = $_POST['civilite'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $autres_prenoms = $_POST['autres_prenoms'];
    $email = $_POST['email'];

    echo "<h1>Merci pour votre candidature !</h1>";
    echo "<p>Bonjour $civilite $prenom $nom, nous avons bien reçu votre demande.</p>";
    echo "<p>Email de contact : $email</p>";

?>