<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use GuzzleHttp\Client;

// Fonction pour rechercher une adresse via la base de données
function searchAddress($query, $type = 'street') {
    $pdo = new PDO("mysql:host=localhost;dbname=geoloc", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT id, name, `groups`, siret, adress, image, manager_last_name, manager_first_name, opening_time, closing_time, latitude, longitude
        FROM pdv
        WHERE `groups` LIKE :query
    ");
    $stmt->execute([
        'query' => '%' . $query . '%'
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)) {
        return $results;
    } else {
        return [];
    }
}

// Fonction pour rechercher une franchise via la base de données
function searchFranchise($query) {
    return searchAddress($query, 'establishment');
}