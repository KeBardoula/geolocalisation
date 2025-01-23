<?php
// Activation du rapportage d'erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarrage de la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclusion du modèle
require_once 'app/Models/update_pdv.php';

class PDVController {
    public function __construct() {
        // Initialisation des variables de session si nécessaire
        if (!isset($_SESSION['search_term'])) {
            $_SESSION['search_term'] = '';
        }
    }

    // Méthode principale de gestion des requêtes
    public function handleRequest() {
        try {
            // Déterminer le type de requête
            $method = $_SERVER['REQUEST_METHOD'];
            
            switch ($method) {
                case 'GET':
                    $this->handleGet();
                    break;
                case 'POST':
                    $this->handlePost();
                    break;
                default:
                    throw new Exception("Méthode non supportée");
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    // Gestion des requêtes GET
    private function handleGet() {
        // Récupération des résultats
        $searchTerm = $_SESSION['search_term'] ?? '';
        
        if (!empty($searchTerm)) {
            $result = $this->searchPDV($searchTerm);
        } else {
            $result = $this->getPDVList();
        }

        // Préparer les données pour la vue
        $data = [
            'searchResults' => $result['results'],
            'totalResults' => $result['total'],
            'searchTerm' => $searchTerm,
            'toastMessage' => $_SESSION['toast_message'] ?? '',
            'toastType' => $_SESSION['toast_type'] ?? ''
        ];

        // Réinitialiser les messages de toast après affichage
        unset($_SESSION['toast_message'], $_SESSION['toast_type']);

        // Inclure la vue
        $this->renderView($data);
    }

    // Gestion des requêtes POST
    private function handlePost() {
        // Vérifier le type de requête POST
        if (isset($_POST['field']) && isset($_POST['id']) && isset($_POST['value'])) {
            $this->handleFieldUpdate();
        } elseif (isset($_POST['search_term'])) {
            $this->handleSearch();
        } else {
            throw new Exception("Requête POST invalide");
        }
    }

    // Mise à jour d'un champ spécifique
    private function handleFieldUpdate() {
        $id = $_POST['id'];
        $field = $_POST['field'];
        $value = $_POST['value'];

        try {
            // Log des données reçues
            error_log("Mise à jour reçue - ID: $id, Field: $field, Value: $value");

            // Validation des données
            if (!$id || !$field || $value === null) {
                throw new Exception("Données invalides");
            }

            // Gestion spécifique pour différents types de champs
            switch ($field) {
                case 'adress':
                    $latitude = $_POST['latitude'] ?? null;
                    $longitude = $_POST['longitude'] ?? null;

                    // Log des coordonnées
                    error_log("Coordonnées - Latitude: $latitude, Longitude: $longitude");

                    $result = $this->updatePDVAddress($id, $value, $latitude, $longitude);
                    break;

                case 'manager':
                    $nameParts = explode(' ', $value, 2);
                    $lastName = $nameParts[0] ?? '';
                    $firstName = $nameParts[1] ?? '';
                    
                    $result = $this->updateManagerName($id, $lastName, $firstName);
                    break;

                default:
                    $result = $this->updateGenericField($id, $field, $value);
            }

            // Stocker le message de succès dans la session
            $_SESSION['toast_message'] = $result ? 'Mise à jour réussie' : 'Échec de la mise à jour';
            $_SESSION['toast_type'] = $result ? 'success' : 'error';

            // Rester sur la même page
            $this->handleGet(); // Appel de la méthode GET pour afficher les résultats
        } catch (Exception $e) {
            // Log et réponse d'erreur
            error_log("Erreur de mise à jour : " . $e->getMessage());
            $_SESSION['toast_message'] = $e->getMessage();
            $_SESSION['toast_type'] = 'error';

            // Rester sur la même page
            $this->handleGet(); // Appel de la méthode GET pour afficher les résultats
        }
    }

    // Gestion de la recherche
    private function handleSearch() {
        $searchTerm = trim($_POST['search_term'] ?? '');
        
        // Stocker le terme de recherche en session
        $_SESSION['search_term'] = $searchTerm;

        // Rester sur la même page
        $this->handleGet(); // Appel de la méthode GET pour afficher les résultats
    }

    // Récupération de la liste des PDV
    private function getPDVList() {
        return getPDVList();
    }

    // Recherche de PDV
    private function searchPDV($searchTerm, $page) {
        return searchPDV($searchTerm);
    }

    // Mise à jour de l'adresse
    private function updatePDVAddress($id, $address, $latitude, $longitude) {
        return updatePDVAddress($id, $address, $latitude, $longitude);
    }

    // Mise à jour du nom du gérant
    private function updateManagerName($id, $lastName, $firstName) {
        $result1 = updatePDVField($id, 'manager_last_name', $lastName);
        $result2 = updatePDVField($id, 'manager_first_name', $firstName);
        return $result1 && $result2;
    }

    // Mise à jour de champ générique
    private function updateGenericField($id, $field, $value) {
        return updatePDVField($id, $field, $value);
    }

    // Rendu de la vue
    private function renderView($data) {
        // Extraction des variables pour la vue
        extract($data);
        
        // Inclusion de la vue
        require 'app/Views/update_pdv.php';
    }

    // Gestion des erreurs
    private function handleError(Exception $e) {
        // Log de l'erreur
        error_log("Erreur dans le contrôleur : " . $e->getMessage());

        // Afficher une page d'erreur
        http_response_code(500);
        echo "Une erreur est survenue : " . $e->getMessage();
        exit();
    }
}

// Exécution du contrôleur
$controller = new PDVController();
$controller->handleRequest();