<?php
session_start();
// On appelle la base de données
require_once 'db.php';

// 1. GESTION DE LA DÉCONNEXION
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: ../html/cours_et_formations.html");
    exit();
}

// 2. GESTION DE LA CONNEXION (Si on vient du formulaire HTML)
if (isset($_POST['emailuser']) && isset($_POST['mdp'])) {
    $email = $_POST['emailuser'];
    $mdp = $_POST['mdp'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Vérification du mot de passe
    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_nom'] = $user['nom'];
    } else {
        die("<h1>Erreur de connexion</h1><p>Email ou mot de passe incorrect.</p><a href='../html/cours_et_formations.html'>Retour</a>");
    }
}

// Sécurité : Si on n'est pas connecté, on dégage vers la page HTML
if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/cours_et_formations.html");
    exit();
}

// ==========================================
// --- ACTIONS DU PROFESSEUR ---
// ==========================================

// Ajouter un créneau
if ($_SESSION['user_role'] == 'professeur' && isset($_POST['date_creneau'])) {
    $stmt = $pdo->prepare("INSERT INTO creneaux (professeur_id, date_creneau, statut) VALUES (?, ?, 'libre')");
    $stmt->execute([$_SESSION['user_id'], $_POST['date_creneau']]);
}

// Supprimer un créneau
if ($_SESSION['user_role'] == 'professeur' && isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id'])) {
    // On supprime uniquement si le créneau appartient à CE professeur
    $stmt = $pdo->prepare("DELETE FROM creneaux WHERE id = ? AND professeur_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    header("Location: permanence.php"); // Rafraîchit la page
    exit();
}

// ==========================================
// --- ACTIONS DE L'ÉTUDIANT ---
// ==========================================

// Réserver un créneau
if ($_SESSION['user_role'] == 'etudiant' && isset($_POST['creneau_id']) && isset($_POST['matiere_id'])) {
    // On ajoute la réservation
    $stmt = $pdo->prepare("INSERT INTO reservations (creneau_id, etudiant_id, matiere_id) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['creneau_id'], $_SESSION['user_id'], $_POST['matiere_id']]);
    // On passe le créneau en "réservé"
    $stmt = $pdo->prepare("UPDATE creneaux SET statut = 'reserve' WHERE id = ?");
    $stmt->execute([$_POST['creneau_id']]);
}

// Annuler une réservation
if ($_SESSION['user_role'] == 'etudiant' && isset($_GET['action']) && $_GET['action'] == 'annuler' && isset($_GET['creneau_id'])) {
    // 1. On vérifie que la réservation appartient bien à cet étudiant
    $stmt = $pdo->prepare("SELECT id FROM reservations WHERE creneau_id = ? AND etudiant_id = ?");
    $stmt->execute([$_GET['creneau_id'], $_SESSION['user_id']]);
    
    if ($stmt->fetch()) {
        // 2. On supprime la réservation
        $stmtDel = $pdo->prepare("DELETE FROM reservations WHERE creneau_id = ? AND etudiant_id = ?");
        $stmtDel->execute([$_GET['creneau_id'], $_SESSION['user_id']]);
        
        // 3. On libère le créneau pour qu'un autre élève puisse le prendre
        $stmtUpd = $pdo->prepare("UPDATE creneaux SET statut = 'libre' WHERE id = ?");
        $stmtUpd->execute([$_GET['creneau_id']]);
    }
    header("Location: permanence.php"); // Rafraîchit la page
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Permanence - Efrei</title>
    <link rel="stylesheet" href="../css/headerfooter.css">
    <link rel="stylesheet" href="../css/permanence.css">
</head>
<body>

    <div id="haut-de-page">
        <header>
            <nav>
                <a href="../html/Ecole.html"><img src="../image/logo_efrei.png" alt="logo_efrei" id="logo"></a>
                <a href="../html/Ecole.html" class="lien">L'ECOLE</a>
                <a href="../html/cours_et_formations.html" class="lien">COURS / FORMATIONS</a>
                <a href="../html/equipe_enseignante.html" class="lien">EQUIPE ENSEIGNANTE</a>
                <a href="../html/contactez_nous.html" class="lien">CONTACTEZ-NOUS</a>
                <a href="../html/a_propos.html" class="lien">A PROPOS</a>
            </nav>
        </header>
    </div>

    <div class="dashboard-container">
        
        <div class="dashboard-header">
            <div>
                <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?> !</h1>
                <p>Espace : <strong><?= ucfirst($_SESSION['user_role']) ?></strong></p>
            </div>
            <a href="permanence.php?action=logout" class="btn-logout">Se déconnecter</a>
        </div>

        <?php if ($_SESSION['user_role'] == 'professeur'): ?>
            
            <h2 class="section-title">Proposer un nouveau créneau</h2>
            <form method="POST" action="permanence.php" class="form-inline">
                <label>Date et heure :</label>
                <input type="datetime-local" name="date_creneau" required>
                <button type="submit" class="btn-action">Ajouter la disponibilité</button>
            </form>

            <h2 class="section-title">Mon Emploi du Temps</h2>
            <table>
                <tr>
                    <th>Date & Heure</th>
                    <th>Statut</th>
                    <th>Étudiant inscrit</th>
                    <th>Matière</th>
                    <th>Action</th>
                </tr>
                <?php
                // Le prof voit ses créneaux, les élèves inscrits et le bouton supprimer
                $sql = "SELECT c.id as creneau_id, c.date_creneau, c.statut, u.nom as etudiant_nom, m.nom as matiere_nom 
                        FROM creneaux c 
                        LEFT JOIN reservations r ON c.id = r.creneau_id 
                        LEFT JOIN utilisateur u ON r.etudiant_id = u.id 
                        LEFT JOIN matiere m ON r.matiere_id = m.id 
                        WHERE c.professeur_id = ? ORDER BY c.date_creneau";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['user_id']]);
                while ($row = $stmt->fetch()):
                ?>
                <tr>
                    <td><?= date('d/m/Y à H:i', strtotime($row['date_creneau'])) ?></td>
                    <td>
                        <?php if($row['statut'] == 'libre') echo "<span class='badge-libre'>Libre</span>"; else echo "<span class='badge-reserve'>Réservé</span>"; ?>
                    </td>
                    <td><?= $row['etudiant_nom'] ? htmlspecialchars($row['etudiant_nom']) : '-' ?></td>
                    <td><?= $row['matiere_nom'] ? htmlspecialchars($row['matiere_nom']) : '-' ?></td>
                    <td>
                        <a href="permanence.php?action=supprimer&id=<?= $row['creneau_id'] ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce créneau ?');">
                           Supprimer
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php else: ?>

            <h2 class="section-title">Réserver une permanence</h2>
            <form method="POST" action="permanence.php" class="form-inline">
                
                <select name="creneau_id" required>
                    <option value="">-- Choisir un horaire libre --</option>
                    <?php
                    // Liste des créneaux libres
                    $stmt = $pdo->query("SELECT c.id, c.date_creneau, u.nom as prof_nom FROM creneaux c JOIN utilisateur u ON c.professeur_id = u.id WHERE c.statut = 'libre'");
                    while ($creneau = $stmt->fetch()) {
                        $date = date('d/m/Y H:i', strtotime($creneau['date_creneau']));
                        echo "<option value='{$creneau['id']}'>{$date} - Prof: {$creneau['prof_nom']}</option>";
                    }
                    ?>
                </select>

                <select name="matiere_id" required>
                    <option value="">-- Choisir la matière --</option>
                    <?php
                    // Liste des matières
                    $stmt = $pdo->query("SELECT id, nom FROM matiere");
                    while ($mat = $stmt->fetch()) {
                        echo "<option value='{$mat['id']}'>".htmlspecialchars($mat['nom'])."</option>";
                    }
                    ?>
                </select>

                <button type="submit" class="btn-action">Réserver</button>
            </form>

            <h2 class="section-title">Mes Réservations</h2>
            <table>
                <tr>
                    <th>Date & Heure</th>
                    <th>Professeur</th>
                    <th>Matière</th>
                    <th>Action</th> </tr>
                <?php
                // L'étudiant voit l'historique de ses réservations
                $sql = "SELECT c.id as creneau_id, c.date_creneau, u.nom as prof_nom, m.nom as matiere_nom 
                        FROM reservations r 
                        JOIN creneaux c ON r.creneau_id = c.id 
                        JOIN utilisateur u ON c.professeur_id = u.id 
                        JOIN matiere m ON r.matiere_id = m.id 
                        WHERE r.etudiant_id = ? ORDER BY c.date_creneau";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['user_id']]);
                while ($row = $stmt->fetch()):
                ?>
                <tr>
                    <td><?= date('d/m/Y à H:i', strtotime($row['date_creneau'])) ?></td>
                    <td><?= htmlspecialchars($row['prof_nom']) ?></td>
                    <td><?= htmlspecialchars($row['matiere_nom']) ?></td>
                    <td>
                        <a href="permanence.php?action=annuler&creneau_id=<?= $row['creneau_id'] ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                           Annuler
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php endif; ?>

    </div>

</body>
</html>