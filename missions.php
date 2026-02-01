<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer toutes les demandes ouvertes sauf celles de l'utilisateur
try {
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare('
        SELECT d.*, u.username, u.nom, u.prenom, u.ville,
        (SELECT COUNT(*) FROM mises_en_relation WHERE id_demande = d.id AND statut = "propose") as nb_propositions,
        (SELECT COUNT(*) FROM mises_en_relation WHERE id_demande = d.id AND id_benevole = :user_id1) as deja_propose
        FROM demandes d
        JOIN utilisateurs u ON d.id_demandeur = u.id
        WHERE d.statut = "ouverte" AND d.id_demandeur != :user_id2
        ORDER BY d.date_creation DESC
    ');
    
    $stmt->execute([
        'user_id1' => $user_id,
        'user_id2' => $user_id
    ]);
    
    $demandes = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $demandes = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missions Disponibles</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
        
        <h1>Missions Disponibles</h1>
        
        <?php if (isset($_GET['success'])) : ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <?php if (empty($demandes)) : ?>
            <div class="no-demandes">
                <p>Aucune mission disponible pour le moment.</p>
            </div>
        <?php else : ?>
            <?php foreach ($demandes as $demande) : ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <h2 class="demande-titre"><?php echo htmlspecialchars($demande['titre']); ?></h2>
                        <span class="statut-badge statut-ouverte">Ouverte</span>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Description :</strong> <?php echo nl2br(htmlspecialchars($demande['description'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Ville :</strong> <?php echo htmlspecialchars($demande['ville']); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Durée :</strong> <?php echo htmlspecialchars($demande['duree']); ?>h
                    </div>
                    
                    <div class="demande-info">
                        <strong>Date limite :</strong> <?php echo date('d/m/Y à H:i', strtotime($demande['date_limite'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Points :</strong> <?php echo $demande['points_attribues']; ?> points
                    </div>
                    
                    <div class="demande-info">
                        <strong>Demandeur :</strong> <?php echo htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']); ?> (<?php echo htmlspecialchars($demande['ville']); ?>)
                    </div>
                    
                    <?php if ($demande['nb_propositions'] > 0) : ?>
                        <div class="demande-info">
                            <span class="propositions-badge"><?php echo $demande['nb_propositions']; ?> personne(s) intéressée(s)</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="actions-buttons">
                        <?php if ($demande['deja_propose'] > 0) : ?>
                            <button class="btn-modifier" disabled style="opacity: 0.6; cursor: not-allowed;">
                                Déjà proposé
                            </button>
                        <?php else : ?>
                            <button class="btn-voir-propositions" onclick="if(confirm('Voulez-vous vous proposer pour cette mission ?')) window.location.href='proposer_mission_simple.php?id=<?php echo $demande['id']; ?>'">
                                J'y vais !
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <div class="demande-info" style="color: #999; font-size: 0.9em; margin-top: 10px;">
                        Publiée le <?php echo date('d/m/Y à H:i', strtotime($demande['date_creation'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
