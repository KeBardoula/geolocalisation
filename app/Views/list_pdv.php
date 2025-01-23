<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Points de Vente</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Polices et icônes -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/list_pdv.css">
</head>
<body>
    <div class="container-fluid">
        <div class="position-absolute top-0 start-0 m-3">
            <a href="index.php?component=backoffice" class="btn btn-outline-primary cyberpunk-btn">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 cyberpunk-container">
                <h1 class="cyberpunk-title neon-glow">
                    <i class="bi bi-shop me-3"></i>Liste des Points de Vente
                </h1>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="bi bi-search text-light"></i>
                        </span>
                        <input 
                            type="text" 
                            id="search-input" 
                            class="form-control cyberpunk-search" 
                            placeholder="Rechercher un point de vente..."
                        >
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover table-cyberpunk">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Groupe</th>
                                <th>SIRET</th>
                                <th>Responsable</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pdv-list">
                            <?php foreach ($pdvs as $pdv): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pdv['id']); ?></td>
                                    <td><?php echo htmlspecialchars($pdv['name']); ?></td>
                                    <td><?php echo htmlspecialchars($pdv['groups']); ?></td>
                                    <td><?php echo htmlspecialchars($pdv['siret']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($pdv['manager_last_name'] . ' ' . $pdv['manager_first_name']); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button 
                                                class="btn btn-sm btn-outline-info details-btn" 
                                                title="Détails" 
                                                data-pdv-id="<?php echo $pdv['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($pdv['name']); ?>"
                                                data-groups="<?php echo htmlspecialchars($pdv['groups']); ?>"
                                                data-siret="<?php echo htmlspecialchars($pdv['siret']); ?>"
                                                data-adress="<?php echo htmlspecialchars($pdv['adress']); ?>"
                                                data-manager-last-name="<?php echo htmlspecialchars($pdv['manager_last_name']); ?>"
                                                data-manager-first-name="<?php echo htmlspecialchars($pdv['manager_first_name']); ?>"
                                                data-opening-time="<?php echo htmlspecialchars($pdv['opening_time']); ?>"
                                                data-closing-time="<?php echo htmlspecialchars($pdv['closing_time']); ?>"
                                                data-latitude="<?php echo htmlspecialchars($pdv['latitude']); ?>"
                                                data-longitude="<?php echo htmlspecialchars($pdv['longitude']); ?>"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                         </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Détails Point de Vente -->
    <div class="modal fade" id="pdvDetailsModal" tabindex="-1" aria-labelledby="pdvDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content cyberpunk-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdvDetailsModalLabel">
                        <i class="bi bi-shop me-2 text-primary"></i>
                        Détails du Point de Vente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>ID</strong>
                                <p id="modal-pdv-id" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Nom</strong>
                                <p id="modal-pdv-name" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Groupe</strong>
                                <p id="modal-pdv-groups" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>SIRET</strong>
                                <p id="modal-pdv-siret" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cyberpunk-card">
                                <strong>Responsable</strong>
                                <p id="modal-pdv-manager" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cyberpunk-card">
                                <strong>Adresse</strong>
                                <p id="modal-pdv-adress" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Heure d'ouverture</strong>
                                <p id="modal-pdv-opening-time" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Heure de fermeture</strong>
                                <p id="modal-pdv-closing-time" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Latitude</strong>
                                <p id="modal-pdv-latitude" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Longitude</strong>
                                <p id="modal-pdv-longitude" class="neon-text"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS et dépendances -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la recherche
            document.getElementById('search-input').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#pdv-list tr');
                
                rows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    const rowText = Array.from(cells)
                        .map(cell => cell.textContent.toLowerCase())
                        .join(' ');
                    
                    row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                });
            });

            // Gestion de la modal des détails point de vente
            const detailsBtns = document.querySelectorAll('.details-btn');
            const pdvDetailsModal = new bootstrap.Modal(document.getElementById('pdvDetailsModal'));

            detailsBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Récupérer les données du point de vente à partir des attributs data
                    const pdvId = this.getAttribute('data-pdv-id');
                    const name = this.getAttribute('data-name');
                    const groups = this.getAttribute('data-groups');
                    const siret = this.getAttribute('data-siret');
                    const adress = this.getAttribute('data-adress');
                    const managerLastName = this.getAttribute('data-manager-last-name');
                    const managerFirstName = this.getAttribute('data-manager-first-name');
                    const openingTime = this.getAttribute('data-opening-time');
                    const closingTime = this.getAttribute('data-closing-time');
                    const latitude = this.getAttribute('data-latitude');
                    const longitude = this.getAttribute('data-longitude');

                    // Mettre à jour les éléments de la modal
                    document.getElementById('modal-pdv-id').textContent = pdvId;
                    document.getElementById('modal-pdv-name').textContent = name;
                    document.getElementById('modal-pdv-groups').textContent = groups;
                    document.getElementById('modal-pdv-siret').textContent = siret;
                    document.getElementById('modal-pdv-adress').textContent = adress;
                    document.getElementById('modal-pdv-manager').textContent = managerLastName + ' ' + managerFirstName;
                    document.getElementById('modal-pdv-opening-time').textContent = openingTime;
                    document.getElementById('modal-pdv-closing-time').textContent = closingTime;
                    document.getElementById('modal-pdv-latitude').textContent = latitude;
                    document.getElementById('modal-pdv-longitude').textContent = longitude;

                    // Afficher la modal
                    pdvDetailsModal.show();
                });
            });
        });
    </script>
</body>
</html>