<?php
require_once __DIR__ . '/../Models/create_user.php';

function createUser() {
    $lastName = $_POST['last_name'];
    $firstName = $_POST['first_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $isAdmin = isset($_POST['role-switch']) ? true : false;

    createUserModel($lastName, $firstName, $email, $password, $isAdmin);

    echo 'Utilisateur créé avec succès !';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'createUser':
            createUser();
            break;
        default:
            echo 'Action non reconnue !';
            break;
    }
} else {
    require_once __DIR__ . '/../Views/create_user.php';
}
?>