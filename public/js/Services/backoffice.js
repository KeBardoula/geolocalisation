// Fonction pour effectuer une recherche d'adresse
export function searchAddress(query) {
    return fetch(`Controllers/backoffice.php?action=search&query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .catch(error => console.error('Erreur lors de la recherche:', error));
}

// Fonction pour initialiser la carte
export function initializeMap() {
    const mapContainer = document.getElementById('map');
    const franceCoordinates = [46.603354, 1.888334];
    const defaultZoom = 6;

    const map = L.map(mapContainer).setView(franceCoordinates, defaultZoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    return map;
}