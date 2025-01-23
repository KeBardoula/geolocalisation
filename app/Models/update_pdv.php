<?php
function connectDatabase() {
    $host = 'localhost';
    $dbname = 'geoloc';
    $username = 'root';
    $password = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true  // Connexions persistantes
        ]);
        return $pdo;
    } catch(PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function getPDVList() {
    $pdo = connectDatabase();

    $query = "SELECT SQL_CALC_FOUND_ROWS 
                id, name, `groups`, siret, adress, 
                manager_last_name, manager_first_name 
              FROM pdv 
              ORDER BY id";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer le nombre total de résultats
    $totalQuery = $pdo->query("SELECT FOUND_ROWS() as total");
    $total = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];

    return [
        'results' => $results,
        'total' => $total
    ];
}

function searchPDV($searchTerm) {
    $pdo = connectDatabase();

    $query = "SELECT SQL_CALC_FOUND_ROWS 
                id, name, `groups`, siret, adress, 
                manager_last_name, manager_first_name 
              FROM pdv 
              WHERE name LIKE :search 
              OR `groups` LIKE :search 
              OR siret LIKE :search 
              OR adress LIKE :search
              ORDER BY name";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':search', "%$searchTerm%");
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer le nombre total de résultats
    $totalQuery = $pdo->query("SELECT FOUND_ROWS() as total");
    $total = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];

    return [
        'results' => $results,
        'total' => $total
    ];
}

function updatePDVField($id, $field, $value) {
    $pdo = connectDatabase();

    // Liste des champs autorisés à la modification
    $allowedFields = ['name', 'groups', 'siret', 'adress', 'manager_last_name', 'manager_first_name'];
    
    if (!in_array($field, $allowedFields)) {
        throw new Exception("Champ non autorisé");
    }

    $query = "UPDATE pdv SET `$field` = :value WHERE id = :id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':value', $value);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

function updatePDVAddress($id, $address, $latitude, $longitude) {
    $pdo = connectDatabase();

    $query = "UPDATE pdv SET adress = :address, latitude = :latitude, longitude = :longitude WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':id', $id);
    
    return $stmt->execute();
}

function displaySearchResults($results) {
    if (empty($results)) {
        return '<tr><td colspan="6" class="text-center">Aucun résultat trouvé</td></tr>';
    }

    $output = '';
    foreach ($results as $pdv) {
        $output .= "
        <tr data-id='" . $pdv['id'] . "' data-latitude='" . htmlspecialchars($pdv['latitude'] ?? '') . "' data-longitude='" . htmlspecialchars($pdv['longitude'] ?? '') . "'>
            <td>
                <form method='POST' action=''>
                    <input type='hidden' name='id' value='" . $pdv['id'] . "'>
                    <input type='hidden' name='field' value='name'>
                    <span class='editable-cell'>" . htmlspecialchars($pdv['name']) . "</span>
                    <input type='text' name='value' class='edit-input' style='display:none;' value='" . htmlspecialchars($pdv['name']) . "'>
                </form>
            </td>
            <td>
                <form method='POST' action=''>
                    <input type='hidden' name='id' value='" . $pdv['id'] . "'>
                    <input type='hidden' name='field' value='groups'>
                    <span class='editable-cell'>" . htmlspecialchars($pdv['groups']) . "</span>
                    <input type='text' name='value' class='edit-input' style='display:none;' value='" . htmlspecialchars($pdv['groups']) . "'>
                </form>
            </td>
            <td>
                <form method='POST' action=''>
                    <input type='hidden' name='id' value='" . $pdv['id'] . "'>
                    <input type='hidden' name='field' value='siret'>
                    <span class='editable-cell'>" . htmlspecialchars($pdv['siret']) . "</span>
                    <input type='text' name='value' class='edit-input' style='display:none;' value='" . htmlspecialchars($pdv['siret']) . "'>
                </form>
            </td>
            <td data-field='adress'>
                <form method='POST' action=''>
                    <input type='hidden' name='id' value='" . $pdv['id'] . "'>
                    <input type='hidden' name='field' value='adress'>
                    <span class='editable-cell'>" . htmlspecialchars($pdv['adress']) . "</span>
                    <input type='text' name='value' class='edit-input' style='display:none;' value='" . htmlspecialchars($pdv['adress']) . "'>
                </form>
            </td>
            <td>
                <form method='POST' action=''>
                    <input type='hidden' name='id' value='" . $pdv['id'] . "'>
                    <input type='hidden' name='field' value='manager'>
                    <span class='editable-cell'>" . htmlspecialchars($pdv['manager_last_name'] . ' ' . $pdv['manager_first_name']) . "</span>
                    <input type='text' name='value' class='edit-input' style='display:none;' value='" . htmlspecialchars($pdv['manager_last_name'] . ' ' . $pdv['manager_first_name']) . "'>
                </form>
            </td>
        </tr>";
    }
    return $output;
}