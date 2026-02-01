<?php
include 'config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les infos de l'utilisateur connecté
$stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = :id');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="header-section">
            <h1>Bienvenue <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?> !</h1>
            <div class="success-message">
                Vous êtes connecté en tant que <strong><?php echo htmlspecialchars($user['roles']); ?></strong>
            </div>
        </div>
        
        <div style="text-align: center; margin: 20px 0;">
            <a href="profil.php" style="text-decoration: none;"><button type="button">Mon Profil</button></a>
        </div>

        <?php if (strpos($user['roles'], 'demandeur') !== false) : ?>
            <div style="margin: 30px 0; padding: 25px; background: #ffffff; border: 3px solid #000000; border-radius: 8px;">
                <h2 style="color: #000000; margin-bottom: 15px; font-size: 28px; font-weight: 700;">Mes demandes</h2>
                <p style="color: #000000; margin-bottom: 20px; font-size: 18px; font-weight: 600;">Vous pouvez créer des demandes d'aide.</p>
                <a href="creer_demande.php" style="text-decoration: none;"><button type="button">Créer une demande</button></a>
                <a href="mes_demandes.php" style="text-decoration: none;"><button type="button">Voir mes demandes</button></a>
            </div>
        <?php endif; ?>

        <?php if (strpos($user['roles'], 'benevole') !== false) : ?>
            <div style="margin: 30px 0; padding: 25px; background: #ffffff; border: 3px solid #000000; border-radius: 8px;">
                <h2 style="color: #000000; margin-bottom: 15px; font-size: 28px; font-weight: 700;">Missions disponibles</h2>
                <p style="color: #000000; margin-bottom: 20px; font-size: 18px; font-weight: 600;">Vous pouvez accepter des missions et gagner des points.</p>
                <a href="missions.php" style="text-decoration: none;"><button type="button">Voir les missions disponibles</button></a>
                <a href="mes_missions.php" style="text-decoration: none;"><button type="button">Mes missions acceptées</button></a>
                <a href="boutique.php" style="text-decoration: none;"><button type="button">Boutique</button></a>
            </div>
        <?php endif; ?>

        <a href="logout.php" class="register-link">
            <button type="button" class="register-button">Se déconnecter</button>
        </a>
    </div>
</body>
</html>
