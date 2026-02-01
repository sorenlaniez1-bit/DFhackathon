<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les infos actuelles
try {
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    header('Location: profil.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Modifier mon profil</h1>
        
        <?php if (isset($_GET['error'])) : ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="modifier_profil_process.php">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required value="<?php echo htmlspecialchars($user['nom']); ?>">
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" required value="<?php echo htmlspecialchars($user['prenom']); ?>">
            </div>
            
            <div class="form-group">
                <label for="age">Âge *</label>
                <input type="number" id="age" name="age" min="1" required value="<?php echo $user['age']; ?>">
            </div>
            
            <div class="form-group">
                <label for="ville">Ville *</label>
                <input type="text" id="ville" name="ville" required value="<?php echo htmlspecialchars($user['ville']); ?>">
            </div>
            
            <div class="form-group">
                <label>Rôle(s) *</label>
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 10px; font-weight: normal;">
                        <input type="checkbox" name="roles[]" value="benevole" style="width: auto;" 
                            <?php echo (strpos($user['roles'], 'benevole') !== false) ? 'checked' : ''; ?>>
                        Bénévole (Je veux aider)
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; font-weight: normal;">
                        <input type="checkbox" name="roles[]" value="demandeur" style="width: auto;"
                            <?php echo (strpos($user['roles'], 'demandeur') !== false) ? 'checked' : ''; ?>>
                        Senior (J'ai besoin d'aide)
                    </label>
                </div>
            </div>
            
            <button type="submit">Enregistrer les modifications</button>
        </form>
        
        <a href="profil.php" class="register-link">
            <button type="button" class="register-button">Annuler</button>
        </a>
    </div>
</body>
</html>
