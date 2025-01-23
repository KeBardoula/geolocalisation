<?php
session_start();

require_once 'app/Models/dashboard.php';
require_once 'app/Models/backoffice.php';

// Récupérer les paramètres de la requête
$component = $_GET['component'] ?? '';
$action = $_GET['action'] ?? '';
$query = $_GET['query'] ?? '';

// Traiter la requête
if ($component === 'dashboard' && $action === 'search') {
    $results = searchAddress($query);
    if (is_array($results) && !empty($results)) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($results);
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['error' => 'Requête invalide']);
    }
} elseif ($component === 'backoffice' && $action === 'search') {
    if ($_SESSION['user_role'] === 'admin') {
        $results = searchBackoffice($query);
        if (is_array($results) && !empty($results)) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($results);
        } else {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(['error' => 'Requête invalide']);
        }
    } else {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['error' => 'Accès refusé']);
    }
} else {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Requête invalide']);
}