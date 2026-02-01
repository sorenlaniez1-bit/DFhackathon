<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_demande = intval($_GET['id'] ?? 0);

if ($id_demande <= 0) {
    header('Location: mes_demandes.php');
    exit;
}

// Récupérer la demande et vérifier qu'elle appartient à l'utilisateur
try {
    $stmt = $pdo->prepare('SELECT * FROM demandes WHERE id = :id AND id_demandeur = :user_id');
    $stmt->execute([
        'id' => $id_demande,
        'user_id' => $_SESSION['user_id']
    ]);
    $demande = $stmt->fetch();
    
    if (!$demande) {
        header('Location: mes_demandes.php?error=Demande introuvable');
        exit;
    }
    
    // Récupérer les propositions
    $stmt = $pdo->prepare('
        SELECT mr.*, u.username, u.nom, u.prenom, u.ville, u.age, u.bio
        FROM mises_en_relation mr
        JOIN utilisateurs u ON mr.id_benevole = u.id
        WHERE mr.id_demande = :id_demande AND mr.statut = "propose"
        ORDER BY mr.date_proposition ASC
    ');
    $stmt->execute(['id_demande' => $id_demande]);
    $propositions = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    header('Location: mes_demandes.php?error=Erreur');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propositions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="mes_demandes.php" class="btn-retour">← Retour à mes demandes</a>
        
        <h1>Propositions pour : <?php echo htmlspecialchars($demande['titre']); ?></h1>
        
        <?php if (isset($_GET['success'])) : ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <?php if (empty($propositions)) : ?>
            <div class="no-demandes">
                <p>Aucune proposition pour cette mission pour le moment.</p>
            </div>
        <?php else : ?>
            <?php foreach ($propositions as $prop) : ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <h2 class="demande-titre"><?php echo htmlspecialchars($prop['prenom'] . ' ' . $prop['nom']); ?></h2>
                        <span class="statut-badge statut-ouverte">Proposé</span>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Ville :</strong> <?php echo htmlspecialchars($prop['ville']); ?>
                    </div>
                    
                    <div class="demande-info">
                        <strong>Âge :</strong> <?php echo $prop['age']; ?> ans
                    </div>
                    
                    <?php if (!empty($prop['bio'])) : ?>
                    <div class="demande-info" style="background: #f8f9fa; padding: 15px; margin-top: 10px; border-left: 4px solid #000;">
                        <strong>En savoir plus :</strong><br>
                        <span style="font-style: italic; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($prop['bio'])); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="demande-info">
                        <strong>Proposé le :</strong> <?php echo date('d/m/Y à H:i', strtotime($prop['date_proposition'])); ?>
                    </div>
                    
                    <div class="actions-buttons">
                        <button class="btn-voir-propositions" onclick="if(confirm('Accepter ce bénévole pour cette mission ?')) window.location.href='accepter_benevole_simple.php?id=<?php echo $prop['id']; ?>'">
                            Accepter ce bénévole
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
