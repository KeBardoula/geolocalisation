import { searchAddress, initializeMap } from '../Services/dashboard.js';

document.addEventListener('DOMContentLoaded', function () {
    // Vérifier que le conteneur de la carte existe
    const mapContainer = document.getElementById('map');
    if (!mapContainer) {
        console.error("Le conteneur #map n'existe pas dans le DOM.");
        return;
    }

    // Initialiser la carte
    const map = initializeMap();

    let markers = []; // Tableau pour stocker les marqueurs

    // Fonction pour effacer tous les marqueurs
    function clearMarkers() {
        markers.forEach(marker => map.removeLayer(marker));
        markers = []; // Réinitialiser le tableau des marqueurs
    }

    // Écouter les changements dans la barre de recherche
    const searchBar = document.getElementById('searchBar');
    if (searchBar) {
        searchBar.addEventListener('input', function () {
            const query = this.value;

            // Effacer les anciens marqueurs
            clearMarkers();

            if (query.length > 2) { // Commencer la recherche après 3 caractères
                fetch('api.php?component=backoffice&action=search&query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        data.forEach(location => {
                            const marker = L.marker([location.latitude, location.longitude]).addTo(map)
                                .bindPopup(location.adress)
                                .openPopup();
                            markers.push(marker); // Ajouter le marqueur au tableau
                        });
                    }
                })
                .catch(error => console.error('Erreur lors de la recherche:', error));
            }
        });
    }
});