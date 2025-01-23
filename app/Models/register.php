<?php
// Models/register_model.php

/**
 * Valide les données du formulaire d'inscription.
 *
 * @param string $lastName
 * @param string $firstName
 * @param string $email
 * @param string $password
 * @param string $confirmPassword
 * @return array Tableau des erreurs
 */
function validateRegistration($lastName, $firstName, $email, $password, $confirmPassword) {
    $errors = [];

    // Validation des champs
    if (empty($lastName)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($firstName)) {
        $errors[] = "Le prénom est requis.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide.";
    }
    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    return $errors;
}

/**
 * Enregistre un nouvel utilisateur dans la base de données.
 *
 * @param string $lastName
 * @param string $firstName
 * @param string $email
 * @param string $hashedPassword
 * @return bool Retourne true si l'inscription est réussie, sinon false
 */
function registerUser ($lastName, $firstName, $email, $hashedPassword) {
    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'geoloc';
    $username = 'root';
    $password = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            return false; // L'email existe déjà
        }

        // Insérer l'utilisateur
        $stmt = $pdo->prepare("
            INSERT INTO user (last_name , first_name, email, password, rule)
            VALUES (:last_name, :first_name, :email, :password, 'user')
        ");
        return $stmt->execute([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
    } catch (PDOException $e) {
        return false; // En cas d'erreur
    }
}
?>