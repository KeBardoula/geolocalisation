<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/register.css">
</head>
<body>
    <!-- Conteneur pour le formulaire d'inscription -->
    <div class="register-container">
        <div class="card">
            <h2>Inscription</h2>
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="index.php?component=register" method="POST">
                <div class="mb-3">
                    <label for="last_name" class="form-label">Nom:</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">Prénom:</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class ="form-label">Mot de passe:</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le mot de passe:</label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
            <div class="login-link">
                <p>Déjà un compte ? <a href="index.php?component=login">Se connecter</a></p>
            </div>
        </div>
    </div>
</body>
</html>