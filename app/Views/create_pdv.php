<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Point de Vente</title>
    
    <!-- Bibliothèques CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Personnalisé -->
    <link rel="stylesheet" href="public/css/create_pdv.css">
</head>
<body>
    <div class="container">
        <div class="d-flex">
            <h1>Créer un point de vente</h1>
            <button class="back-button" onclick="window.location.href='index.php?component=backoffice'">
                <i class="bi bi-arrow-left"></i> Retour
            </button>
        </div>

        <form id="create-pdv-form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="createPDV">

            <div class="form-group">
                <label for="name">Nom du Point de Vente</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    required 
                    maxlength="100"
                    placeholder="Nom du point de vente"
                >
            </div>

            <div class="form-group">
                <label for="groups">Groupe</label>
                <input 
                    type="text" 
                    id="groups" 
                    name="groups" 
                    required 
                    maxlength="50"
                    placeholder="Nom du groupe"
                >
            </div>

            <div class="form-group">
                <label for="siret">Numéro SIRET</label>
                <input 
                    type="text" 
                    id="siret" 
                    name="siret" 
                    required 
                    pattern="\d{14}" 
                    maxlength="14"
                    placeholder="14 chiffres"
                >
            </div>

            <div class="form-group">
                <label for="adress">Adresse</label>
                <input 
                    type="text" 
                    id="adress" 
                    name="adress" 
                    required 
                    maxlength="255"
                    placeholder="Adresse complète"
                >
            </div>

            <div class="form-group">
                <label for="image">Image du Point de Vente</label>
                <input 
                    type="file" 
                    id="image" 
                    name="image" 
                    accept="image/*"
                >
            </div>

            <div class="form-group">
                <label for="manager_last_name">Nom du Gérant</label>
                <input 
                    type="text" 
                    id="manager_last_name" 
                    name="manager_last_name" 
                    required 
                    maxlength="50"
                    placeholder="Nom de famille"
                >
            </div>

            <div class="form-group">
                <label for="manager_first_name">Prénom du Gérant</label>
                <input 
                    type="text" 
                    id="manager_first_name" 
                    name="manager_first_name" 
                    required 
                    maxlength="50"
                    placeholder="Prénom"
                >
            </div>

            <div class="form-group">
                <label for="opening_time">Heure d'Ouverture</label>
                <input 
                    type="time" 
                    id="opening_time" 
                    name="opening_time" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="closing_time">Heure de Fermeture</label>
                <input 
                    type="time" 
                    id="closing_time" 
                    name="closing_time" 
                    required
                >
            </div>

            <div class="form-group">
                <input type="submit" value="Créer le Point de Vente">
            </div>
        </form>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div 
            id="successToast" 
            class="toast custom-toast" 
            role="alert" 
            aria-live="assertive" 
            aria-atomic="true"
        >
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <small>À l'instant</small>
                <button 
                    type="button" 
                    class="btn-close" 
                    data-bs-dismiss="toast" 
                    aria-label="Close"
                ></button>
            </div>
            <div class="toast-body" id="toastMessage">
                <!-- Message dynamique -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction de géocodage asynchrone
            async function geocodeAddress(address) {
                try {
                    const response = await axios.get('https://nominatim.openstreetmap.org/search', {
                        params: {
                            q: address,
                            format: 'json',
                            limit: 1
                        },
                        headers: {
                            'User-Agent': 'YourApplicationName/1.0'
                        }
                    });

                    if (response.data && response.data.length > 0) {
                        const location = response.data[0];
                        return {
                            latitude: parseFloat(location.lat),
                            longitude: parseFloat(location.lon),
                            formattedAddress: location.display_name
                        };
                    } else {
                        throw new Error('Adresse non trouvée');
                    }
                } catch (error) {
                    console.error('Erreur de géocodage:', error);
                    return null;
                }
            }

            // Fonction pour afficher les notifications toast
            function showToast(message, isSuccess = true) {
                const toastEl = document.getElementById('successToast');
                const toastBody = document.getElementById('toastMessage');
                
                toastBody.innerHTML = `
                    <div class="d-flex align-items-center ${isSuccess ? 'text-success' : 'text-danger'}">
                        <i class="bi bi-${isSuccess ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                        ${message}
                    </div>
                `;
                
                toastEl.classList.remove(isSuccess ? 'bg-danger' : 'bg-success');
                toastEl.classList.add(isSuccess ? 'bg-success' : 'bg-danger');

                const toast = new bootstrap.Toast(toastEl, {
                    animation: true,
                    autohide: true,
                    delay: 5000
                });
                toast.show();
            }

            // Gestion de la soumission du formulaire
            document.getElementById('create-pdv-form').addEventListener('submit', async function(event) {
                event.preventDefault();

                // Récupérer les données du formulaire
                const formData = new FormData(this);
                const address = formData.get('adress');

                try {
                    // Tentative de géocodage
                    const geoResult = await geocodeAddress(address);

                    if (!geoResult) {
                        showToast('Impossible de géolocaliser l\'adresse', false);
                        return;
                    }

                    // Ajouter les coordonnées GPS au formulaire
                    formData.append('latitude', geoResult.latitude);
                    formData.append('longitude', geoResult.longitude);

                    // Envoi de la requête AJAX
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'app/Controllers/create_pdv.php', true);
                    
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            // Succès
                            showToast(xhr.responseText);
                            
                            // Réinitialiser le formulaire
                            event.target.reset();

                            // Afficher l'adresse géolocalisée
                            showToast(`Adresse géolocalisée : ${geoResult.formattedAddress}`);
                        } else {
                            // Erreur
                            showToast(xhr.responseText || 'Une erreur est survenue', false);
                        }
                    };

                    xhr.onerror = function() {
                        showToast('Erreur de connexion au serveur', false);
                    };

                    xhr.send(formData);

                } catch (error) {
                    console.error('Erreur lors de la soumission du formulaire:', error);
                    showToast('Erreur lors de la soumission du formulaire', false);
                }
            });

            // Gestion du téléchargement de fichier
            document.getElementById('image').addEventListener('change', function(e) {
                if (this.files && this.files.length > 0) {
                    const fileName = this.files[0].name;
                    const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2); // Taille en Mo

                    // Créer un élément pour afficher les informations du fichier
                    const fileInfoSpan = document.createElement('small');
                    fileInfoSpan.classList.add('text-muted', 'd-block', 'mt-2');
                    fileInfoSpan.innerHTML = `
                        <i class="bi bi-file-earmark-text"></i> 
                        ${fileName} (${fileSize} Mo)
                    `;

                    // Supprimer l'ancien élément s'il existe
                    const existingFileInfo = this.parentNode.querySelector('small');
                    if (existingFileInfo) {
                        existingFileInfo.remove();
                    }

                    // Ajouter les informations du fichier
                    this.parentNode.insertBefore(fileInfoSpan, this.nextSibling);
                }
            });
        });

        // Ajout de la bibliothèque Axios pour les requêtes HTTP
        document.addEventListener('DOMContentLoaded', function() {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/axios/dist/axios.min.js';
            document.head.appendChild(script);
        });
    </script>
</body>
</html>