<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer toutes les demandes de l'utilisateur
try {
    $stmt = $pdo->prepare('
        SELECT d.*, 
        (SELECT COUNT(*) FROM mises_en_relation WHERE id_demande = d.id AND statut = "propose") as nb_propositions,
        u.username, u.nom as benevole_nom, u.prenom as benevole_prenom
        FROM demandes d
        LEFT JOIN utilisateurs u ON d.id_benevole = u.id
        WHERE d.id_demandeur = :user_id
        ORDER BY d.date_creation DESC
    ');
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
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
    <title>Mes Demandes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
        
        <h1>Mes Demandes</h1>
        
        <?php if (isset($_GET['commentaire_success'])) : ?>
            <div class="success-message">Commentaire publié avec succès !</div>
        <?php endif; ?>
        
        <?php if (empty($demandes)) : ?>
            <div class="no-demandes">
                <p>Vous n'avez créé aucune demande pour le moment.</p>
                <a href="creer_demande.php"><button type="button">Créer une demande</button></a>
            </div>
        <?php else : ?>
            <?php foreach ($demandes as $demande) : ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <h2 class="demande-titre"><?php echo htmlspecialchars($demande['titre']); ?></h2>
                        <span class="statut-badge statut-<?php echo $demande['statut']; ?>">
                            <?php 
                                $statuts = [
                                    'ouverte' => 'Ouverte',
                                    'prise_en_charge' => 'En cours',
                                    'terminee' => 'Terminée',
                                    'depassee' => 'Dépassée'
                                ];
                                echo $statuts[$demande['statut']] ?? $demande['statut'];
                            ?>
                        </span>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Description :</strong> <?php echo nl2br(htmlspecialchars($demande['description'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Où :</strong> <?php echo htmlspecialchars($demande['lieu']); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Durée :</strong> <?php echo htmlspecialchars($demande['duree']); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Date limite :</strong> <?php echo date('d/m/Y à H:i', strtotime($demande['date_limite'])); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Points :</strong> <?php echo $demande['points_attribues']; ?> points
                    </div>
                    
                    <?php if ($demande['statut'] === 'ouverte' && $demande['nb_propositions'] > 0) : ?>
                        <div class="demande-info">
                            <span class="propositions-badge"><?php echo $demande['nb_propositions']; ?> proposition(s)</span>
                            <button class="btn-voir-propositions" onclick="window.location.href='voir_propositions.php?id=<?php echo $demande['id']; ?>'">
                                Voir les propositions
                            </button>
                        </div>
                    <?php elseif ($demande['statut'] === 'prise_en_charge') : ?>
                        <div class="demande-info">
                            <strong>Bénévole :</strong> <?php echo htmlspecialchars($demande['benevole_prenom'] . ' ' . $demande['benevole_nom']); ?>
                            <button class="btn-terminer" onclick="if(confirm('Confirmer que la mission est terminée ?')) window.location.href='terminer_mission_simple.php?id=<?php echo $demande['id']; ?>'">
                                Marquer comme terminée
                            </button>
                        </div>
                    <?php elseif ($demande['statut'] === 'terminee' && $demande['id_benevole']) : ?>
                        <div class="demande-info">
                            <strong>Bénévole :</strong> <?php echo htmlspecialchars($demande['benevole_prenom'] . ' ' . $demande['benevole_nom']); ?>
                        </div>
                        <div class="actions-buttons">
                            <button onclick="window.location.href='commenter_benevole.php?id=<?php echo $demande['id']; ?>'" style="background: #3d5a80; color: #fff;">
                                <?php echo $demande['commentaire_benevole'] ? 'Modifier le commentaire' : 'Laisser un commentaire sur le bénévole'; ?>
                            </button>
                        </div>
                        <?php if ($demande['commentaire_benevole']) : ?>
                            <div class="demande-info" style="background: #2d4263; padding: 10px; margin-top: 10px; border-left: 4px solid #60a5fa; color: #ffffff;">
                                <strong style="color: #60a5fa;">Votre commentaire :</strong><br>
                                <span style="font-style: italic; color: #ffffff;"><?php echo nl2br(htmlspecialchars($demande['commentaire_benevole'])); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="demande-info" style="color: #999; font-size: 0.9em;">
                        Créée le <?php echo date('d/m/Y à H:i', strtotime($demande['date_creation'])); ?>
                    </div>
                    
                    <?php if ($demande['statut'] === 'ouverte') : ?>
                        <div class="actions-buttons">
                            <button class="btn-modifier" onclick="window.location.href='modifier_demande.php?id=<?php echo $demande['id']; ?>'">
                                Modifier
                            </button>
                            <button class="btn-supprimer" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')) window.location.href='supprimer_demande.php?id=<?php echo $demande['id']; ?>'">
                                Supprimer
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
