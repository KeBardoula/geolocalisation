<?php
require_once 'app/Models/delete_user.php';

// Gestion des actions
$action = $_GET['action'] ?? '';
$message = '';
$messageType = '';

// Suppression d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $userId = $_POST['delete_user_id'];
    $result = deleteUser($userId);
    
    if ($result) {
        $message = "Utilisateur supprimé avec succès.";
        $messageType = "success";
    } else {
        $message = "Erreur lors de la suppression de l'utilisateur.";
        $messageType = "danger";
    }
}

// Récupérer la liste des utilisateurs
$users = getAllUsers();

// Inclure la vue
include 'app/Views/delete_user.php';