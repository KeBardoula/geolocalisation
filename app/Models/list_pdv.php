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

function getAllPDV() {
    $pdo = connectDatabase();
    $query = $pdo->prepare("SELECT * FROM pdv");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function filterPDV($searchTerm) {
    $pdo = connectDatabase();
    $query = $pdo->prepare("
        SELECT * FROM pdv 
        WHERE name LIKE :search 
        OR `groups` LIKE :search 
        OR siret LIKE :search
        OR manager_last_name LIKE :search
        OR manager_first_name LIKE :search
    ");
    $query->execute(['search' => "%$searchTerm%"]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}