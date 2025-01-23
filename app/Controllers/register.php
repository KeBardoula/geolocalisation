<?php
// Inclure le modèle
require 'app/Models/register.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $lastName = $_POST['last_name'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Valider les données
    $errors = validateRegistration($lastName, $firstName, $email, $password, $confirmPassword);

    if (empty($errors)) {
        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérer l'utilisateur dans la base de données
        $success = registerUser ($lastName, $firstName, $email, $hashedPassword);

        if ($success) {
            // Rediriger vers la page de connexion après une inscription réussie
            header('Location: index.php?component=login');
            exit();
        } else {
            $errors[] = "Une erreur s'est produite lors de l'inscription.";
        }
    }
}

// Inclure la vue
require 'app/Views/register.php';
?>