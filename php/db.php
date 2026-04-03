<?php
$host = 'localhost';
$dbname = 'gestion_permanence';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Create DB if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci");
    $pdo->exec("USE `$dbname`");
    
    // Create tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS utilisateur (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            mot_de_passe VARCHAR(255) NOT NULL,
            role ENUM('etudiant', 'professeur') NOT NULL DEFAULT 'etudiant'
        );
        CREATE TABLE IF NOT EXISTS matiere (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL
        );
        CREATE TABLE IF NOT EXISTS creneaux (
            id INT AUTO_INCREMENT PRIMARY KEY,
            professeur_id INT NOT NULL,
            date_creneau DATETIME NOT NULL,
            statut ENUM('libre', 'reserve') NOT NULL DEFAULT 'libre',
            FOREIGN KEY (professeur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
        );
        CREATE TABLE IF NOT EXISTS reservations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            creneau_id INT NOT NULL,
            etudiant_id INT NOT NULL,
            matiere_id INT NOT NULL,
            FOREIGN KEY (creneau_id) REFERENCES creneaux(id) ON DELETE CASCADE,
            FOREIGN KEY (etudiant_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
            FOREIGN KEY (matiere_id) REFERENCES matiere(id) ON DELETE CASCADE
        );
    ");

    // Insertion des matières réelles de l'Efrei
    $stmt = $pdo->query("SELECT COUNT(*) FROM matiere WHERE nom = 'Administration système'");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("DELETE FROM matiere"); // On supprime les anciennes de test (ex: Maths, Physique)
        $pdo->exec("ALTER TABLE matiere AUTO_INCREMENT = 1");
        $pdo->exec("INSERT INTO matiere (nom) VALUES 
            ('Administration système'), 
            ('Base de données : concepts de base'), 
            ('Cybersécurité'), 
            ('Réseaux'), 
            ('Algorithme et Programmation en C'), 
            ('Virtualisation et conteneurisation')
        ");
    }

    // Insertion de comptes par défaut (un étudiant et un professeur)
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateur");
    if ($stmt->fetchColumn() == 0) {
        $stmt_insert = $pdo->prepare("INSERT INTO utilisateur (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        
        // Compte élève (Mot de passe: eleve)
        $mdp_eleve = password_hash('eleve', PASSWORD_DEFAULT);
        $stmt_insert->execute(['Élève Démo', 'eleve@efrei.fr', $mdp_eleve, 'etudiant']);
        
        // Compte prof (Mot de passe: prof)
        $mdp_prof = password_hash('prof', PASSWORD_DEFAULT);
        $stmt_insert->execute(['Professeur Démo', 'prof@efrei.fr', $mdp_prof, 'professeur']);
    }

} catch(PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
