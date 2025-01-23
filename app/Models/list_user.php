<?php
function connectDatabase() {
    $host = 'localhost';
    $dbname = 'geoloc';
    $username = 'root';
    $password = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function getAllUsers() {
    $pdo = connectDatabase();
    $query = $pdo->prepare("SELECT * FROM user");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function filterUsers($searchTerm) {
    $pdo = connectDatabase();
    $query = $pdo->prepare("
        SELECT * FROM user 
        WHERE last_name LIKE :search 
        OR first_name LIKE :search 
        OR email LIKE :search
    ");
    $query->execute(['search' => "%$searchTerm%"]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}