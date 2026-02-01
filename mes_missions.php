<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les missions acceptées par le bénévole
try {
    $stmt = $pdo->prepare('
        SELECT d.*, u.nom, u.prenom, u.ville as ville_demandeur
        FROM demandes d
        JOIN utilisateurs u ON d.id_demandeur = u.id
        WHERE d.id_benevole = :id
        AND d.statut IN ("prise_en_charge", "terminee")
        ORDER BY d.date_creation DESC
    ');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $missions = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $missions = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Missions Acceptées</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
        
        <h1>Mes Missions Acceptées</h1>
        
        <?php if (isset($_GET['success'])) : ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <?php if (empty($missions)) : ?>
            <div class="no-demandes">
                <p>Vous n'avez pas encore de mission acceptée.</p>
                <p><a href="missions.php" style="color: #000; text-decoration: underline; font-weight: 600;">Voir les missions disponibles</a></p>
            </div>
        <?php else : ?>
            <?php foreach ($missions as $mission) : ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <h2 class="demande-titre"><?php echo htmlspecialchars($mission['titre']); ?></h2>
                        <?php if ($mission['statut'] == 'prise_en_charge') : ?>
                            <span class="statut-badge statut-prise_en_charge">En cours</span>
                        <?php else : ?>
                            <span class="statut-badge statut-terminee">Terminée</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Description :</strong> <?php echo nl2br(htmlspecialchars($mission['description'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Ville :</strong> <?php echo htmlspecialchars($mission['ville']); ?>
                    </div>
                    
                    <div class="demande-info" style="background: #2d4263; padding: 10px; border-radius: 8px; border: 2px solid #60a5fa; color: #ffffff;">
                        <strong style="color: #60a5fa;">📍 Adresse complète :</strong> <?php echo htmlspecialchars($mission['lieu']); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Durée :</strong> <?php echo htmlspecialchars($mission['duree']); ?>h
                    </div>
                    
                    <div class="demande-info">
                        <strong>Date limite :</strong> <?php echo date('d/m/Y à H:i', strtotime($mission['date_limite'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Points à gagner :</strong> <?php echo $mission['points_attribues']; ?> points
                    </div>
                    
                    <div class="demande-info">
                        <strong>Demandeur :</strong> <?php echo htmlspecialchars($mission['prenom'] . ' ' . $mission['nom']); ?>
                    </div>
                    
                    <div class="demande-info" style="color: #666; font-size: 14px;">
                        Acceptée le <?php echo date('d/m/Y à H:i', strtotime($mission['date_creation'])); ?>
                    </div>
                    
                    <?php if ($mission['statut'] == 'prise_en_charge') : ?>
                        <div class="actions-buttons">
                            <p style="color: #ffffff; font-weight: 600; margin-bottom: 10px;">Cette mission est en cours. Le demandeur vous contactera et validera la mission une fois terminée.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
