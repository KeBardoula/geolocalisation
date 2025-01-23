<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche Points de Vente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/update_pdv.css">
</head>
<body>
    <!-- Toasts pour les messages de succès et d'erreur -->
    <div class="toast" id="successToast" style="position: absolute; top: 20px; right: 20px;">
        <div class="toast-body" id="successToastBody"></div>
    </div>
    <div class="toast" id="errorToast" style="position: absolute; top: 20px; right: 20px;">
        <div class="toast-body" id="errorToastBody"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="index.php?component=backoffice" class="back-button">Retour</a>
                <div class="search-container">
                    <h2 class="text-center mb-4">Recherche de Points de Vente</h2>
                    
                    <!-- Formulaire de recherche -->
                    <form method="POST" action="">
                        <div class="input-group mb-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Rechercher par nom, groupe, SIRET ou adresse" 
                                name="search_term" 
                                value="<?= htmlspecialchars($searchTerm) ?>"
                            >
                            <button class="btn btn-primary" type="submit">Rechercher</button>
                        </div>
                    </form>

                    <!-- Gestion des messages d'erreur -->
                    <?php if(isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error_message'] ?>
                            <?php unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success_message'] ?>
                            <?php unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error_message'] ?>
                            <?php unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Tableau des résultats -->
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Groupe</th>
                                        <th>SIRET</th>
                                        <th>Adresse</th>
                                        <th>Gérant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= displaySearchResults($searchResults) ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la recherche d'adresse -->
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addressModalLabel">Recherche et Modification d'Adresse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="addressSearchInput">Adresse</label>
                        <input type="text" id="addressSearchInput" class="form-control" placeholder="Entrez une adresse">
                    </div>
                    <button id="searchAddressBtn" class="btn btn-primary mt-2">Rechercher</button>
                    <div id="searchResults" class="list-group mt-3"></div>
                    <div id="mapContainer" style="height: 400px; margin-top: 20px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="saveAddressBtn" class="btn btn-primary">Enregistrer l'adresse</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        let currentPDVId = null;
        let currentCell = null;
        let map = null;
        let marker = null;
        let selectedAddress = null;
        let selectedLatitude = null;
        let selectedLongitude = null;

        // Initialisation de la carte
        function initMap(lat, lon) {
            // Supprimer la carte existante si elle existe
            if (map) {
                map.remove();
            }
            
            // Créer une nouvelle carte
            map = L.map('mapContainer').setView([lat, lon], 13);
            
            // Ajouter les tuiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Ajouter un marqueur
            marker = L.marker([lat, lon]).addTo(map);
        }

        // Gestion de l'édition en ligne
        document.querySelectorAll('.editable-cell').forEach(cell => {
            cell.addEventListener('dblclick', function() {
                // Vérifier si c'est la cellule d'adresse
                if (this.closest('td').dataset.field === 'adress') {
                    const row = this.closest('tr');
                    currentPDVId = row.dataset.id;
                    currentCell = this;

                    // Récupérer les coordonnées actuelles
                    const lat = parseFloat(row.dataset.latitude || 48.8566); // Coordonnées par défaut de Paris
                    const lon = parseFloat(row.dataset.longitude || 2.3522);

                    // Initialiser la carte avec les coordonnées actuelles
                    initMap(lat, lon);

                    // Afficher l'adresse actuelle dans l'input
                    document.getElementById('addressSearchInput').value = this.textContent.trim();

                    // Ouvrir la modal
                    const modal = new bootstrap.Modal(document.getElementById('addressModal'));
                    modal.show();
                } else {
                    // Logique d'édition standard pour les autres cellules
                    const form = this.closest('form');
                    const span = this;
                    const input = form.querySelector('.edit-input');
                    span.style.display = 'none';
                    input.style.display = 'block';
                    input.focus();

                    input.addEventListener('blur', function() {
                        form.submit();
                    });

                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            form.submit();
                        }
                    });
                }
            });
        });

        // Recherche d'adresse
        document.getElementById('searchAddressBtn').addEventListener('click', function() {
            const addressQuery = document.getElementById('addressSearchInput').value.trim();
            
            if (!addressQuery) return;

            // Requête vers Nominatim OpenStreetMap
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(addressQuery)}`)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('searchResults');
                    resultsContainer.innerHTML = '';

                    if (data.length === 0) {
                        resultsContainer.innerHTML = '<div class="alert alert-warning">Aucun résultat trouvé</div>';
                        return;
                    }

                    data.forEach(result => {
                        const resultItem = document.createElement('a');
                        resultItem.href = '#';
                        resultItem.className = 'list-group-item list-group-item-action';
                        resultItem.textContent = result.display_name;
                        
                        resultItem.addEventListener('click', function(e) {
                            e.preventDefault();
                            
                            // Mettre à jour la carte
                            const lat = parseFloat(result.lat);
                            const lon = parseFloat(result.lon);
                            
                            // Centrer la carte et placer le marqueur
                            map.setView([lat, lon], 13);
                            
                            if (marker) {
                                map.removeLayer(marker);
                            }
                            
                            marker = L.marker([lat, lon]).addTo(map);

                            // Stocker les informations
                            selectedAddress = result.display_name;
                            selectedLatitude = lat;
                            selectedLongitude = lon;

                            // Mettre à jour l'input
                            document.getElementById('addressSearchInput').value = selectedAddress;
                        });

                        resultsContainer.appendChild(resultItem);
                    });
                })
                .catch(error => {
                    console.error('Erreur de recherche:', error);
                });
        });

        // Enregistrement de l'adresse
        document.getElementById('saveAddressBtn').addEventListener('click', function() {
            if (!selectedAddress) {
                alert('Veuillez sélectionner une adresse');
                return;
            }

            // Préparer les données pour l'envoi
            const formData = new FormData();
            formData.append('id', currentPDVId);
            formData.append('field', 'adress');
            formData.append('value', selectedAddress);
            formData.append('latitude', selectedLatitude);
            formData.append('longitude', selectedLongitude);

            // Envoi de la requête
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Vérifier si la réponse est OK
                if (!response.ok) {
                    throw new Error('Erreur de réponse du serveur');
                }
                return response.json();
            })
            .then(data => {
                // Vérifier la structure de la réponse
                if (data && data.success) {
                    // Mettre à jour la cellule
                    currentCell.textContent = selectedAddress;
                    const row = currentCell.closest('tr');
                    row.dataset.latitude = selectedLatitude;
                    row.dataset.longitude = selectedLongitude;

                    // Fermer la modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
                    if (modal) {
                        modal.hide();
                    }

                    // Afficher un message de succès
                    showToast('success', 'Adresse mise à jour avec succès');
                } else {
                    // Gérer les erreurs de la réponse
                    throw new Error(data.message || 'Erreur lors de la mise à jour');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('error', error.message || 'Erreur lors de la mise à jour');
            });
        });

        // Fonction pour afficher les toasts
        function showToast(type, message) {
            const toastEl = type === 'success' 
                ? document.getElementById('successToast') 
                : document.getElementById('errorToast');
            
            const toastBodyEl = type === 'success' 
                ? document.getElementById('successToastBody') 
                : document.getElementById('errorToastBody');
            
            if (toastEl && toastBodyEl) {
                toastBodyEl.textContent = message;
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        }
    });
    </script>
</body>
</html>