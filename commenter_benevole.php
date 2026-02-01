<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_demande = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_demande <= 0) {
    header('Location: dashboard.php');
    exit();
}

// Vérifier que la demande existe et appartient au demandeur connecté
$stmt = $pdo->prepare("SELECT d.*, u.prenom, u.nom 
                       FROM demandes d 
                       LEFT JOIN utilisateurs u ON d.id_benevole = u.id 
                       WHERE d.id = :id AND d.id_demandeur = :user_id AND d.statut = 'terminee'");
$stmt->execute([
    'id' => $id_demande,
    'user_id' => $_SESSION['user_id']
]);
$demande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$demande) {
    header('Location: dashboard.php');
    exit();
}

if (!$demande['id_benevole']) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commenter le bénévole</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="demandes-container">
        <a href="dashboard.php" class="btn-retour">← Retour au tableau de bord</a>
        
        <h1>Laisser un commentaire sur le bénévole</h1>
        
        <div class="demande-card">
            <h3><?php echo htmlspecialchars($demande['titre']); ?></h3>
            <p><strong>Bénévole :</strong> <?php echo htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']); ?></p>
            <p><strong>Statut :</strong> Mission terminée</p>
        </div>

        <form action="commenter_benevole_process.php" method="POST">
            <input type="hidden" name="id_demande" value="<?php echo $id_demande; ?>">
            
            <label for="commentaire">Votre commentaire sur ce bénévole :</label>
            <textarea 
                name="commentaire" 
                id="commentaire" 
                rows="6" 
                required
                placeholder="Décrivez comment s'est passée la mission avec ce bénévole..."><?php echo htmlspecialchars($demande['commentaire_benevole'] ?? ''); ?></textarea>
            
            <button type="submit">
                <?php echo $demande['commentaire_benevole'] ? 'Modifier le commentaire' : 'Publier le commentaire'; ?>
            </button>
        </form>

        <?php if ($demande['commentaire_benevole']): ?>
        <div class="info-message">
            Ce commentaire sera visible sur le profil du bénévole.
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
