<?php
require_once 'app/Models/list_pdv.php';

// Charger tous les points de vente
$pdvs = getAllPDV();

// Inclure la vue
include 'app/Views/list_pdv.php';