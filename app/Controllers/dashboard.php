<?php
error_reporting(0);
// Inclure le modèle avec un chemin absolu
require_once __DIR__ . '/../Models/dashboard.php';

// Fonction pour afficher la vue
function displayDashboard() {
    // Charger la vue avec un chemin absolu
    require_once __DIR__ . '/../Views/dashboard.php';
}

// Fonction pour rechercher une adresse
function searchAddressController() {
    $query = $_GET['query'] ?? '';
    $results = searchAddress($query);
    header('Content-Type: application/json'); // Ajoutez cette ligne
    echo json_encode($results);
}

// Fonction pour rechercher une franchise
function searchFranchiseController() {
    $query = $_GET['query'] ?? '';
    $results = searchFranchise($query);
    header('Content-Type: application/json');
    echo json_encode($results);
}

// Exécuter la logique en fonction de l'action
if (isset($_GET['action']) && $_GET['action'] === 'search') {
    searchAddressController();
} elseif (isset($_GET['action']) && $_GET['action'] === 'searchFranchise') {
    searchFranchiseController();
} else {
    displayDashboard();
}

if (isset($_POST['action']) && $_POST['action'] === 'getData') {
    $id = $_POST['id'] ?? 0;
    $data = [
        'status' => 'success',
        'message' => 'Données reçues',
        'id' => $id
    ];
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>