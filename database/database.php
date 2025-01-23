<?php
// Importer Faker
require __DIR__ . '/../vendor/autoload.php'; // Assurez-vous que le chemin est correct
use Faker\Factory;

// Configuration de la base de données
$host = 'localhost'; // Hôte de la base de données
$dbname = 'geoloc'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = 'root'; // Mot de passe MySQL

try {
    // Connexion à MySQL sans sélectionner de base de données
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base de données
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");

    // Créer la table `user`
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            last_name VARCHAR(50) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            rule ENUM('user', 'admin') NOT NULL DEFAULT 'user'
        )
    ");

    // Créer la table `pdv`
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS pdv (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            `groups` VARCHAR(50) NOT NULL, -- Échapper le mot-clé groups
            siret VARCHAR(14) NOT NULL UNIQUE,
            adress VARCHAR(255) NOT NULL,
            image VARCHAR(255),
            manager_last_name VARCHAR(50) NOT NULL,
            manager_first_name VARCHAR(50) NOT NULL,
            opening_time TIME NOT NULL,
            closing TIME NOT NULL,
            latitude DECIMAL(10, 8) NOT NULL,
            longitude DECIMAL(11, 8) NOT NULL
        )
    ");

    echo "Base de données et tables créées avec succès.\n";

    // Initialiser Faker
    $faker = Factory::create('fr_FR'); // Utiliser le français pour les données

    // Insérer 50 utilisateurs (dont 2 admins)
    for ($i = 1; $i <= 50; $i++) {
        $lastName = $faker->lastName;
        $firstName = $faker->firstName;
        $email = $faker->unique()->email;
        $password = password_hash('password123', PASSWORD_BCRYPT); // Mot de passe par défaut
        $rule = ($i <= 2) ? 'admin' : 'user'; // Les 2 premiers sont des admins

        $stmt = $pdo->prepare("
            INSERT INTO user (last_name, first_name, email, password, rule)
            VALUES (:last_name, :first_name, :email, :password, :rule)
        ");
        $stmt->execute([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $email,
            'password' => $password,
            'rule' => $rule,
        ]);
    }

    echo "50 utilisateurs insérés avec succès.\n";

    // Insérer des points de vente (PDV)
    $franchises = [
        'McDonald\'s',
        'Quick',
        'Burger King',
        'KFC',
        'Subway',
        'Carrefour',
        'Auchan',
        'Leclerc',
        'Lidl',
        'Aldi',
        'Intermarché',
        'Super U',
        'Monoprix',
        'Franprix',
        'Casino',
        'Géant Casino',
        'Hyper U',
        'E.Leclerc',
        'Carrefour Market',
        'Carrefour City',
        'Eglise',
        'Mosquée',
        'Synagogue',
        'Temple',
        'Cathédrale',
        'Chapelle',
        'Basilique',
        'Ecole',
        'Collège',
        'Lycée',
        'Université',
        'Mairie',
        'Préfecture',
        'Ambassade',
        'Consulat',
        'Palais de justice',
        'Tribunal',
        'Caserne',
        'Commissariat',
        'Gendarmerie',
        'Pompiers',
        'Hôpital',
        'Clinique',
        'Cabinet médical',
        'Pharmacie',
        'Laboratoire',
        'Cabinet',
        'Tour Eiffel',
        'Arc de Triomphe',
        'Mont Saint-Michel',
        'Cité de Carcassonne',
        'Pont du Gard',
        'Palais des Papes',
        'Cathédrale de Chartres',
        'Château',
        'Tabac',
        'Bar',
        'Café',
        'Restaurant',
        'Fast-food',
        'Pizzeria',
        'Kebab',
        'Boulangerie',
        'Pâtisserie',
        'Boucherie',
        'Charcuterie',
        'Traiteur',
        'Supermarché',
        'Hypermarché',
        'Marché',
        'Foire',
        'Salon',
        'Exposition',
        'Musée',
        'Cinéma',
        'Théâtre',
        'Opéra',
        'Salle de concert',
        'Stade',
        'Gymnase',
        'Piscine',
        'Patinoire',
        'Bowling',
        'Salle de sport'
    ];

    for ($i = 1; $i <= 1000; $i++) {
        $name = $faker->company;
        $groups = $faker->randomElement($franchises);
        $siret = $faker->unique()->numerify('##############'); // 14 chiffres
        $adress = $faker->address;
        $image = $faker->imageUrl(640, 480, 'business');
        $managerLastName = $faker->lastName;
        $managerFirstName = $faker->firstName;
        $openingTime = $faker->time('H:i:s');
        $closingTime = $faker->time('H:i:s');
        $latitude = $faker->latitude(42.0, 51.0); // Latitude minimale : 42.0, latitude maximale : 51.0
        $longitude = $faker->longitude(-5.0, 10.0); // Longitude minimale : -5.0, longitude maximale : 10.0

        $stmt = $pdo->prepare("
            INSERT INTO pdv (name, `groups`, siret, adress, image, manager_last_name, manager_first_name, opening_time, closing, latitude, longitude)
            VALUES (:name, :groups, :siret, :adress, :image, :manager_last_name, :manager_first_name, :opening_time, :closing, :latitude, :longitude)
        ");
        $stmt->execute([
            'name' => $name,
    'groups' => $groups,
            'siret' => $siret,
            'adress' => $adress,
            'image' => $image,
            'manager_last_name' => $managerLastName,
            'manager_first_name' => $managerFirstName,
            'opening_time' => $openingTime,
            'closing' => $closingTime,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    echo "1000 points de vente insérés avec succès, incluant les coordonnées.\n";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>