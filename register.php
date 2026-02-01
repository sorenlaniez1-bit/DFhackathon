<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Créer un compte</h1>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error-message" role="alert">Erreur : ' . htmlspecialchars($_GET['error']) . '</div>';
        }
        if (isset($_GET['success'])) {
            echo '<div class="success-message" role="alert">Compte créé avec succès ! Vous pouvez vous connecter.</div>';
        }
        ?>
        <form method="POST" action="register_process.php">
            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="age">Âge</label>
                <input type="number" id="age" name="age" min="1" required>
            </div>
            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" required>
            </div>
            <div class="form-group">
                <label>Rôle(s)</label>
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 10px; font-weight: normal;">
                        <input type="checkbox" name="roles[]" value="benevole" style="width: auto;">
                        Bénévole (Je veux aider)
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; font-weight: normal;">
                        <input type="checkbox" name="roles[]" value="demandeur" style="width: auto;">
                        Senior (J'ai besoin d'aide)
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit">Créer le compte</button>
        </form>
        <a href="login.php" class="register-link">
            <button type="button" class="register-button">J'ai déjà un compte</button>
        </a>
    </div>
</body>
</html>
