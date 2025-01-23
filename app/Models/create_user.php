<?php
function createUserModel($lastName, $firstName, $email, $password, $isAdmin) {
    // Configuration de la base de données
    $host = 'localhost'; // Hôte de la base de données
    $dbname = 'geoloc'; // Nom de la base de données
    $username = 'root'; // Nom d'utilisateur MySQL
    $dbPassword = 'root'; // Mot de passe MySQL

    try {
        // Connexion à MySQL sans sélectionner de base de données
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si l'utilisateur existe déjà dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        if (!$user) {
            // Insérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO user (last_name, first_name, email, password, rule) VALUES (:last_name, :first_name, :email, :password, :rule)");
            $stmt->execute([
                'last_name' => $lastName,
                'first_name' => $firstName,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'rule' => $isAdmin ? 'admin' : 'user'
            ]);
        } else {
            echo "L'utilisateur existe déjà.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>