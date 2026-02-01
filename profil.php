<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les infos de l'utilisateur
try {
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    // Récupérer le solde de points
    $stmt = $pdo->prepare('SELECT points FROM points WHERE id_utilisateur = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $solde = $stmt->fetch();
    $points = $solde ? $solde['points'] : 0;
    
    // Statistiques demandes créées
    $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM demandes WHERE id_demandeur = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $stats_demandes = $stmt->fetch();
    
    // Statistiques missions réalisées en tant que bénévole
    $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM demandes WHERE id_benevole = :id AND statut = "terminee"');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $stats_missions = $stmt->fetch();
    
    // Points gagnés
    $stmt = $pdo->prepare('SELECT SUM(montant) as total FROM transactions_points WHERE id_utilisateur = :id AND type = "gain"');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $points_gagnes = $stmt->fetch();
    
    // Points dépensés
    $stmt = $pdo->prepare('SELECT SUM(montant) as total FROM transactions_points WHERE id_utilisateur = :id AND type = "depense"');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $points_depenses = $stmt->fetch();
    
    // Récupérer les commentaires reçus en tant que bénévole
    $stmt = $pdo->prepare('
        SELECT d.commentaire_benevole, d.titre, u.prenom, u.nom, d.date_creation
        FROM demandes d
        JOIN utilisateurs u ON d.id_demandeur = u.id
        WHERE d.id_benevole = :id 
        AND d.commentaire_benevole IS NOT NULL 
        AND d.commentaire_benevole != ""
        ORDER BY d.date_creation DESC
    ');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $commentaires = $stmt->fetchAll();
    
} catch (PDOException $e) {
    echo "ERREUR: " . $e->getMessage();
    error_log($e->getMessage());
    exit;
    // header('Location: dashboard.php');
    // exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
        
        <h1>Mon Profil</h1>
        
        <?php if (isset($_GET['success'])) : ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <!-- Informations personnelles -->
        <div class="demande-card">
            <div class="demande-header">
                <h2 class="demande-titre">Informations personnelles</h2>
                <button class="btn-modifier" onclick="window.location.href='modifier_profil.php'">
                    Modifier
                </button>
            </div>
            
            <div class="demande-info">
                <strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?>
            </div>
            
            <div class="demande-info">
                <strong>Prénom :</strong> <?php echo htmlspecialchars($user['prenom']); ?>
            </div>
            
            <div class="demande-info">
                <strong>Identifiant :</strong> <?php echo htmlspecialchars($user['username']); ?>
            </div>
            
            <div class="demande-info">
                <strong>Âge :</strong> <?php echo $user['age']; ?> ans
            </div>
            
            <div class="demande-info">
                <strong>Ville :</strong> <?php echo htmlspecialchars($user['ville']); ?>
            </div>
            
            <div class="demande-info">
                <strong>Rôles :</strong> <?php echo htmlspecialchars($user['roles']); ?>
            </div>
            
            <?php if (!empty($user['bio'])) : ?>
            <div class="demande-info" style="background: #2d4263; padding: 15px; margin-top: 10px; border-left: 4px solid #60a5fa; color: #ffffff;">
                <strong style="color: #60a5fa;">En savoir plus :</strong><br>
                <span style="font-style: italic; line-height: 1.8; color: #ffffff;"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></span>
            </div>
            <?php endif; ?>
            
            <div class="demande-info" style="color: #999; font-size: 0.9em;">
                Membre depuis le <?php echo date('d/m/Y', strtotime($user['date_creation'])); ?>
            </div>
        </div>
        
        <!-- Solde de points -->
        <div class="demande-card" style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h2 style="margin: 0; color: white;">Solde actuel</h2>
            <div style="font-size: 3em; font-weight: bold; margin: 10px 0;"><?php echo $points; ?> points</div>
            <a href="boutique.php"><button class="btn-voir-propositions" style="background: white; color: #667eea;">Accéder à la boutique</button></a>
        </div>
        
        <!-- Statistiques -->
        <div class="demande-card">
            <h2 class="demande-titre">Statistiques</h2>
            
            <div class="demande-info">
                <strong>Demandes créées :</strong> <?php echo $stats_demandes['total']; ?>
            </div>
            
            <div class="demande-info">
                <strong>Missions réalisées :</strong> <?php echo $stats_missions['total']; ?>
            </div>
            
            <div class="demande-info">
                <strong>Points gagnés (total) :</strong> <?php echo $points_gagnes['total'] ?? 0; ?> points
            </div>
            
            <div class="demande-info">
                <strong>Points dépensés (total) :</strong> <?php echo $points_depenses['total'] ?? 0; ?> points
            </div>
        </div>
        
        <!-- Mes achats récents -->
        <div class="demande-card">
            <h2 class="demande-titre">Mes derniers achats</h2>
            
            <?php
            try {
                $stmt = $pdo->prepare('
                    SELECT a.*, b.nom, b.prix_points
                    FROM achats a
                    JOIN boutique b ON a.id_article = b.id
                    WHERE a.id_utilisateur = :id
                    ORDER BY a.date_achat DESC
                    LIMIT 5
                ');
                $stmt->execute(['id' => $_SESSION['user_id']]);
                $achats = $stmt->fetchAll();
            } catch (PDOException $e) {
                $achats = [];
            }
            ?>
            
            <?php if (empty($achats)) : ?>
                <div class="demande-info" style="color: #999;">
                    Aucun achat pour le moment.
                </div>
            <?php else : ?>
                <?php foreach ($achats as $achat) : ?>
                    <div class="demande-info">
                        • <?php echo htmlspecialchars($achat['nom']); ?> 
                        <span style="color: #999;">(<?php echo $achat['prix_points']; ?> points - <?php echo date('d/m/Y', strtotime($achat['date_achat'])); ?>)</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Commentaires reçus -->
        <?php if (!empty($commentaires)) : ?>
        <div class="demande-card">
            <h2 class="demande-titre">Commentaires reçus</h2>
            
            <?php foreach ($commentaires as $com) : ?>
                <div class="demande-card" style="margin-top: 15px; background: #f8f9fa; border-left: 4px solid #000;">
                    <div class="demande-info" style="margin-bottom: 10px;">
                        <strong>Mission :</strong> <?php echo htmlspecialchars($com['titre']); ?>
                    </div>
                    <div class="demande-info" style="margin-bottom: 10px;">
                        <strong>Par :</strong> <?php echo htmlspecialchars($com['prenom'] . ' ' . $com['nom']); ?>
                        <?php if ($com['date_creation']) : ?>
                            <span style="color: #999; font-size: 0.9em;">
                                (<?php echo date('d/m/Y', strtotime($com['date_creation'])); ?>)
                            </span>
                        <?php endif; ?>
                    </div>
                    <div style="font-style: italic; padding: 10px; background: white; border: 2px solid #000; margin-top: 10px;">
                        <?php echo nl2br(htmlspecialchars($com['commentaire_benevole'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
