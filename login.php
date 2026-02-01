

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Connexion</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="login-container">
		<h1>Connexion</h1>
		<?php
		// Afficher un message d'erreur si présent
		if (isset($_GET['error'])) {
			echo '<div class="error-message" role="alert">Identifiants incorrects</div>';
		}
		// Afficher un message de succès si présent
		if (isset($_GET['success'])) {
			echo '<div class="success-message" role="alert">Inscription réussie ! Vous pouvez vous connecter.</div>';
		}
		?>
		<form method="POST" action="login_process.php">
			<div class="form-group">
				<label for="username">Identifiant</label>
				<input 
					type="text" 
					id="username" 
					name="username" 
					required 
					autocomplete="username"
					aria-required="true">
			</div>
			<div class="form-group">
				<label for="password">Mot de passe</label>
				<input 
					type="password" 
					id="password" 
					name="password" 
					required 
					autocomplete="current-password"
					aria-required="true">
			</div>
			<button type="submit">Se connecter</button>
		</form>
		<a href="register.php" class="register-link">
			<button type="button" class="register-button">Je n'ai pas encore de compte</button>
		</a>
	</div>
</body>
</html>
