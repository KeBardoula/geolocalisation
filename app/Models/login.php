<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'geoloc';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

/**
 * Vérifie les informations de connexion et retourne l'utilisateur si elles sont valides
 */
function verifyLogin($email, $password) {
    global $pdo;

    // Récupérer l'utilisateur par email
    $stmt = $pdo->prepare("SELECT id, password, rule FROM user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier le mot de passe
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Retourne l'utilisateur si les informations sont valides
    }

    return false; // Retourne false si les informations sont invalides
}
?>