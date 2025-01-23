<?php
session_start();

$component = isset($_GET['component']) ? $_GET['component'] : 'login'; // Par défaut, afficher la page de connexion

if (isset($_GET['component']) && $_GET['component'] === 'backoffice' && $_SESSION['user_role'] !== 'admin') {
    die('Accès refusé');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géolocalisation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Contenu de la page -->
    <?php
        // Charger le composant approprié
        switch ($component) {
            case 'register':
                require 'app/Controllers/register.php'; // Charge le contrôleur d'inscription
                break;
            case 'login':
                require 'app/Controllers/login.php'; // Charge le contrôleur de connexion
                break;
            case 'dashboard':
                require 'app/Controllers/dashboard.php'; // Charge le contrôleur du tableau de bord
                break;
            case 'backoffice':
                require 'app/Controllers/backoffice.php'; // Charge le contrôleur du backoffice
                break;
            case 'create_user':
                require 'app/Controllers/create_user.php'; // Charge le contrôleur de la création d'utilisateur
                break;
            case 'create_pdv':
                require 'app/Controllers/create_pdv.php'; // Charge le contrôleur de la création de PDV
                break;
            case 'update_user':
                require 'app/Controllers/update_user.php'; // Charge le contrôleur de la modification d'utilisateur
                break;
            case 'update_pdv':
                require 'app/Controllers/update_pdv.php'; // Charge le contrôleur de la modification des points de vente
                break;
            case 'list_user':
                require 'app/Controllers/list_user.php'; // Charge le contrôleur du listing des utilisateurs
                break;
            case 'list_pdv':
                require 'app/Controllers/list_pdv.php'; // Charge le contrôleur du listing des points de vente
                break;
            case 'delete_user':
                require 'app/Controllers/delete_user.php'; // Charge le contrôleur de suppression d'utilisateur
                break;
            case 'delete_pdv':
                require 'app/Controllers/delete_pdv.php'; // Charge le contrôleur de point de vente
                break;
            default:
                require 'app/Controllers/login.php'; // Charge la vue de connexion par défaut
                break;
        }
    ?>
    <script src="public/js/script_backoffice.js"></script>
    <script src="public/js/script_dashboard.js"></script>
</body>
</html>