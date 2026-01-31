<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="style.php">
</head>
<body>
    <div class="login-container">
        <h1>Bienvenue</h1>
        
        <div class="success-message">
            Vous êtes connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
        </div>

        <a href="logout.php" class="register-link">
            <button type="button" class="register-button">Se déconnecter</button>
        </a>
    </div>
</body>
</html>
