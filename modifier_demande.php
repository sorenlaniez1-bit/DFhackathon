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

// Récupérer la demande
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
    
    // Ne peut modifier que si statut = ouverte
    if ($demande['statut'] !== 'ouverte') {
        header('Location: mes_demandes.php?error=Cette demande ne peut plus être modifiée');
        exit;
    }
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
    <title>Modifier la demande</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Modifier la demande</h1>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="modifier_demande_process.php">
            <input type="hidden" name="id" value="<?php echo $demande['id']; ?>">
            
            <div class="form-group">
                <label for="titre">Titre de la demande *</label>
                <input type="text" id="titre" name="titre" required maxlength="255" value="<?php echo htmlspecialchars($demande['titre']); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required rows="5"><?php echo htmlspecialchars($demande['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="lieu">Où (lieu) *</label>
                <input type="text" id="lieu" name="lieu" required maxlength="255" value="<?php echo htmlspecialchars($demande['lieu']); ?>">
            </div>
            
            <div class="form-group">
                <label for="duree">Durée estimée *</label>
                <input type="text" id="duree" name="duree" required maxlength="100" value="<?php echo htmlspecialchars($demande['duree']); ?>">
            </div>
            
            <div class="form-group">
                <label for="date_limite">Date limite *</label>
                <input type="datetime-local" id="date_limite" name="date_limite" required value="<?php echo date('Y-m-d\TH:i', strtotime($demande['date_limite'])); ?>">
            </div>
            
            <button type="submit">Enregistrer les modifications</button>
        </form>
        
        <a href="mes_demandes.php" class="register-link">
            <button type="button" class="register-button">Annuler</button>
        </a>
    </div>
</body>
</html>
