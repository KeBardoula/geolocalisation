<?php
require_once 'app/Models/update_user.php';

function handleUpdateUserRequest() {
    // Variable pour stocker les messages
    $message = '';
    $messageType = '';
    $users = [];

    // Traitement des actions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        if ($action === 'search') {
            $searchTerm = $_POST['search'] ?? '';
            $users = performUserSearch($searchTerm);
        } elseif ($action === 'update_field') {
            $userId = $_POST['user_id'] ?? '';
            $field = $_POST['field'] ?? '';
            $value = $_POST['value'] ?? '';

            // Gestion spéciale pour le mot de passe
            if ($field === 'password') {
                $password = $_POST['value'] ?? '';
                $passwordConfirm = $_POST['password_confirm'] ?? '';

                // Validation du mot de passe
                if (empty($password)) {
                    $message = 'Le mot de passe ne peut pas être vide';
                    $messageType = 'error';
                } elseif ($password !== $passwordConfirm) {
                    $message = 'Les mots de passe ne correspondent pas';
                    $messageType = 'error';
                } elseif (strlen($password) < 8) {
                    $message = 'Le mot de passe doit contenir au moins 8 caractères';
                    $messageType = 'error';
                } else {
                    // Mise à jour du mot de passe
                    $result = updateUserField($userId, $field, $password);

                    if ($result) {
                        $message = 'Mot de passe mis à jour avec succès';
                        $messageType = 'success';
                    } else {
                        $message = 'Erreur lors de la mise à jour du mot de passe';
                        $messageType = 'error';
                    }
                }
            } else {
                // Mise à jour des autres champs
                $result = updateUserField($userId, $field, $value);

                if ($result) {
                    $message = 'Mise à jour réussie';
                    $messageType = 'success';
                } else {
                    $message = 'Erreur lors de la mise à jour';
                    $messageType = 'error';
                }
            }

            // Rechercher à nouveau les utilisateurs pour rafraîchir la liste
            $users = performUserSearch('');
        }
    } else {
        // Afficher tous les utilisateurs par défaut
        $users = performUserSearch('');
    }

    include 'app/Views/update_user.php';
}

// Appel de la fonction de gestion des requêtes
handleUpdateUserRequest();