<?php
// Activation du rapport d'erreurs complet
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Activation de l'affichage des erreurs
ini_set('display_startup_errors', 1);

// Log des erreurs
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/create_pdv_error.log');

require_once __DIR__ . '/../Models/create_pdv.php';

function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, __DIR__ . '/create_pdv_error.log');
}

function createPDV() {
    try {
        // Log initial des données reçues
        logError("Données reçues POST: " . print_r($_POST, true));
        logError("Données reçues FILES: " . print_r($_FILES, true));

        // Vérification des champs requis
        $requiredFields = [
            'name', 'groups', 'siret', 'adress', 
            'manager_last_name', 'manager_first_name', 
            'opening_time', 'closing_time', 
            'latitude', 'longitude'
        ];

        // Vérifier que tous les champs sont présents
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            logError("Champs manquants : " . implode(', ', $missingFields));
            http_response_code(400);
            echo "Champs manquants : " . implode(', ', $missingFields);
            exit;
        }

        // Nettoyage et validation des données
        $name = trim($_POST['name']);
        $groups = trim($_POST['groups']);
        $siret = preg_replace('/\s+/', '', $_POST['siret']);
        $adress = trim($_POST['adress']);
        $managerLastName = trim($_POST['manager_last_name']);
        $managerFirstName = trim($_POST['manager_first_name']);
        $openingTime = $_POST['opening_time'];
        $closingTime = $_POST['closing_time'];
        
        // Validation SIRET
        if (!preg_match('/^\d{14}$/', $siret)) {
            logError("SIRET invalide : " . $siret);
            http_response_code(400);
            echo "Numéro SIRET invalide";
            exit;
        }

        // Validation des coordonnées GPS
        $latitude = filter_var($_POST['latitude'], FILTER_VALIDATE_FLOAT, [
            'options' => ['min_range' => -90, 'max_range' => 90]
        ]);
        $longitude = filter_var($_POST['longitude'], FILTER_VALIDATE_FLOAT, [
            'options' => ['min_range' => -180, 'max_range' => 180]
        ]);

        if ($latitude === false || $longitude === false) {
            logError("Coordonnées GPS invalides");
            http_response_code(400);
            echo "Coordonnées GPS invalides";
            exit;
        }

        // Gestion de l'image
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/pdv/';
            
            // Créer le dossier s'il n'existe pas
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    logError("Impossible de créer le dossier d'upload");
                    http_response_code(500);
                    echo "Erreur de configuration du serveur";
                    exit;
                }
            }

            // Validation du fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxFileSize = 5 * 1024 * 1024; // 5 Mo

            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                logError("Type de fichier non autorisé : " . $_FILES['image']['type']);
                http_response_code(400);
                echo "Type de fichier non autorisé";
                exit;
            }

            if ($_FILES['image']['size'] > $maxFileSize) {
                logError("Fichier trop volumineux : " . $_FILES['image']['size']);
                http_response_code(400);
                echo "Fichier trop volumineux";
                exit;
            }

            // Générer un nom de fichier unique
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $fileExtension;
            $imagePath = $uploadDir . $imageName;

            // Déplacer le fichier téléchargé
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                logError("Échec du déplacement du fichier");
                http_response_code(500);
                echo "Erreur lors du téléchargement de l'image";
                exit;
            }
        }

        // Appel du modèle
        try {
            $result = createPDVModel(
                $name, 
                $groups, 
                $siret, 
                $adress, 
                $imagePath, 
                $managerLastName, 
                $managerFirstName, 
                $openingTime, 
                $closingTime, 
                $latitude, 
                $longitude
            );

            if ($result) {
                http_response_code(200);
                echo 'Point de Vente créé avec succès !';
            } else {
                logError("Échec de création du PDV dans le modèle");
                http_response_code(500);
                echo 'Erreur lors de la création du Point de Vente.';
            }
        } catch (Exception $modelException) {
            logError("Erreur du modèle : " . $modelException->getMessage());
            http_response_code(500);
            echo 'Erreur de base de données : ' . $modelException->getMessage();
        }

    } catch (Exception $e) {
        // Capture des erreurs inattendues
        logError("Erreur inattendue : " . $e->getMessage());
        http_response_code(500);
        echo 'Erreur interne du serveur : ' . $e->getMessage();
    }
}

// Gestionnaire de requêtes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'createPDV':
            createPDV();
            break;
        default:
            logError("Action non reconnue : " . $action);
            http_response_code(400);
            echo 'Action non reconnue !';
            break;
    }
} else {
    // Affichage du formulaire
    require_once __DIR__ . '/../Views/create_pdv.php';
}
?>