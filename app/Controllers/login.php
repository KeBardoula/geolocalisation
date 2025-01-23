<?php
// Inclure le modèle
require 'app/Models/login.php';

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Valider les données
    if (empty($email) || empty($password)) {
        $errors[] = "Veuillez remplir tous les champs.";
    } else {
        // Vérifier les informations de connexion
        $user = verifyLogin($email, $password);

        if ($user) {
            // Démarrer la session
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['rule'];

            // Rediriger en fonction du rôle
            if ($user['rule'] === 'admin') {
                header('Location: index.php?component=backoffice');
                exit();
            } elseif ($user['rule'] === 'user') {
                header('Location: index.php?component=dashboard');
                exit();
            } else {
                $errors[] = "Rôle utilisateur non reconnu.";
            }
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}

// Inclure la vue
require 'app/Views/login.php';
?>