<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les missions pour lesquelles le bénévole s'est proposé (en attente)
try {
    $stmt = $pdo->prepare('
        SELECT d.*, u.nom, u.prenom, u.ville as ville_demandeur, mr.date_proposition, mr.statut as statut_proposition
        FROM mises_en_relation mr
        JOIN demandes d ON mr.id_demande = d.id
        JOIN utilisateurs u ON d.id_demandeur = u.id
        WHERE mr.id_benevole = :id
        AND mr.statut = "propose"
        AND d.statut = "ouverte"
        ORDER BY mr.date_proposition DESC
    ');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $propositions = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $propositions = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Propositions en Attente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
        
        <h1>Mes Propositions en Attente</h1>
        
        <p style="text-align: center; color: #ffffff; font-size: 18px; margin-bottom: 30px; font-weight: 600;">
            Vous vous êtes proposé pour ces missions. Le demandeur n'a pas encore donné sa réponse.
        </p>
        
        <?php if (empty($propositions)) : ?>
            <div class="no-demandes">
                <p>Vous n'avez aucune proposition en attente.</p>
                <p><a href="missions.php" style="color: #000; text-decoration: underline; font-weight: 600;">Voir les missions disponibles</a></p>
            </div>
        <?php else : ?>
            <?php foreach ($propositions as $proposition) : ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <h2 class="demande-titre"><?php echo htmlspecialchars($proposition['titre']); ?></h2>
                        <span class="statut-badge propositions-badge">En attente</span>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Description :</strong> <?php echo nl2br(htmlspecialchars($proposition['description'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Ville :</strong> <?php echo htmlspecialchars($proposition['ville']); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Durée :</strong> <?php echo htmlspecialchars($proposition['duree']); ?>h
                    </div>
                    
                    <div class="demande-info">
                        <strong>Date limite :</strong> <?php echo date('d/m/Y à H:i', strtotime($proposition['date_limite'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Points à gagner :</strong> <?php echo $proposition['points_attribues']; ?> points
                    </div>
                    
                    <div class="demande-info">
                        <strong>Demandeur :</strong> <?php echo htmlspecialchars($proposition['prenom'] . ' ' . $proposition['nom']); ?>
                    </div>
                    
                    <div class="demande-info" style="background: #e0f2fe; padding: 10px; border-radius: 8px; border: 2px solid #000; margin-top: 15px;">
                        <strong>📅 Proposition envoyée le :</strong> <?php echo date('d/m/Y à H:i', strtotime($proposition['date_proposition'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
