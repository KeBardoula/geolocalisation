<?php
require_once 'app/config/database.php';

function performUserSearch($searchTerm) {
    global $pdo;
    
    // Si la recherche est vide, retourner tous les utilisateurs
    if (empty($searchTerm)) {
        $query = "SELECT * FROM user";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Logique de recherche existante
    $searchTerm = "%{$searchTerm}%";
    $query = "SELECT * FROM user WHERE 
              last_name LIKE :search OR 
              first_name LIKE :search OR 
              email LIKE :search";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':search', $searchTerm);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserField($userId, $field, $value) {
    global $pdo;
    
    // Liste des champs autorisés pour éviter les injections
    $allowedFields = ['last_name', 'first_name', 'email', 'rule', 'password'];
    
    if (!in_array($field, $allowedFields)) {
        error_log("Tentative de mise à jour d'un champ non autorisé : " . $field);
        return false;
    }

    // Préparation de la requête
    $query = "UPDATE user SET $field = :value WHERE id = :userId";
    $stmt = $pdo->prepare($query);
    
    // Hachage du mot de passe si le champ est 'password'
    if ($field === 'password') {
        $value = password_hash($value, PASSWORD_BCRYPT);
    }

    // Exécution de la requête
    $stmt->bindParam(':value', $value);
    $stmt->bindParam(':userId', $userId);
    
    return $stmt->execute();
}