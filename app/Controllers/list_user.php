<?php
require_once 'app/Models/list_user.php';

// Charger tous les utilisateurs
$users = getAllUsers();

// Inclure la vue
include 'app/Views/list_user.php';