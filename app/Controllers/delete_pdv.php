<?php
require_once 'app/Models/delete_pdv.php';

// Gestion des actions
$action = $_GET['action'] ?? '';
$message = '';
$messageType = '';

// Suppression d'un point de vente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_pdv_id'])) {
    $pdvId = $_POST['delete_pdv_id'];
    $result = deletePDV($pdvId);
    
    if ($result) {
        $message = "Point de vente supprimé avec succès.";
        $messageType = "success";
    } else {
        $message = "Erreur lors de la suppression du point de vente.";
        $messageType = "danger";
    }
}

// Récupérer la liste des points de vente
$pdvs = getAllPDV();

// Inclure la vue
include 'app/Views/delete_pdv.php';