<?php
function createPDVModel(
    $name, 
    $groups, 
    $siret, 
    $adress, 
    $imagePath, 
    $managerLastName, 
    $managerFirstName, 
    $openingTime , 
    $closingTime, 
    $latitude, 
    $longitude
) {
    // Configuration de la base de données
    $host = 'localhost';
    $dbname = 'geoloc';
    $username = 'root';
    $dbPassword = 'root';

    try {
        // Connexion à MySQL 
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si le point de vente existe déjà dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM pdv WHERE siret = :siret");
        $stmt->execute(['siret' => $siret]);

        $pdv = $stmt->fetch();

        if (!$pdv) {
            // Insérer le point de vente dans la base de données
            $stmt = $pdo->prepare("INSERT INTO pdv (name, `groups`, siret, adress, image, manager_last_name, manager_first_name, opening_time, closing_time, latitude, longitude) VALUES (:name, :groups, :siret, :adress, :image, :manager_last_name, :manager_first_name, :opening_time, :closing_time, :latitude, :longitude)");
            $stmt->execute([
                'name' => $name,
                'groups' => $groups,
                'siret' => $siret,
                'adress' => $adress,
                'image' => $imagePath,
                'manager_last_name' => $managerLastName,
                'manager_first_name' => $managerFirstName,
                'opening_time' => $openingTime,
                'closing_time' => $closingTime,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
        } else {
            echo "Le Point de Vente existe déjà.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>