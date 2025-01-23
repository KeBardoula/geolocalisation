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

function deletePDV($pdvId) {
    $pdo = connectDatabase();
    
    try {
        $query = $pdo->prepare("DELETE FROM pdv WHERE id = :id");
        $result = $query->execute(['id' => $pdvId]);
        
        return $result ? true : false;
    } catch(PDOException $e) {
        error_log("Erreur de suppression : " . $e->getMessage());
        return false;
    }
}