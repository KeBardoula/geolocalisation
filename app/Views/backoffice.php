<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <!-- Inclure le CSS de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Inclure le CSS de Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Inclure le CSS de l'application -->
    <link rel="stylesheet" href="public/css/backoffice.css">
    <link rel="stylesheet" href="public/css/navbar.css">
    <link rel="stylesheet" href="public/css/footer.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
</head>
<body>
    <?php include 'public/_partials/navbar.php'; ?>
    <!-- Container principal -->
    <div class="container-fluid">
        <!-- En-tête -->
        <header class="bg-dark p-3">
            <h1 class="text-center">Tableau de bord</h1>
        </header>
        <!-- Barre de recherche -->
        <div class="row mt-3">
            <div class="col-md-6 offset-md-3">
                <input type="text" id="searchBar" class="form-control" placeholder="Rechercher une adresse...">
            </div>
        </div>
        <!-- Carte -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div id="map" class="h-600 w-80" style="border: 1px solid black;"></div>
            </div>
        </div>
    </div>

    <?php include 'public/_partials/footer.php'; ?>

    <!-- Inclure le JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Inclure le JavaScript de Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Inclure le module JavaScript -->
    <script type="module" src="public/js/Modules/backoffice.js"></script>
</body>
</html>