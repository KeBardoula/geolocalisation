<!DOCTYPE html>
<html>
<head>
    <title>Créer un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/create_user.css">
</head>
<body>
    <div class="container">
        <div class="d-flex align-items-center">
            <h1 class="mb-0">Créer un utilisateur</h1>
           <button class="back-button ms-2" onclick="window.location.href='index.php?component=backoffice'">
                <i class="bi bi-arrow-left"></i>Retour
            </button>
        </div>
        <form id="create-user-form" method="POST">
            <input type="hidden" name="action" value="createUser">
            <div class="form-group">
                <label for="last_name">Nom :</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="first_name">Prénom :</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="role-switch" name="role-switch">
                <label class="form-check-label" for="role-switch"></label>
            </div>
            <input type="submit" value="Créer">
        </form>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast custom-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="white"></rect>
                </svg>
                <strong class="me-auto">Succès</strong>
                <small>À l'instant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                <!-- Message sera inséré ici -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('create-user-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'app/Controllers/create_user.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Afficher le toast de succès
                    var toastEl = document.getElementById('successToast');
                    var toastBody = document.getElementById('toastMessage');
                    
                    // Personnalisation du message
                    toastBody.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle me-2"></i>
                            ${xhr.responseText}
                        </div>
                    `;

                    var toast = new bootstrap.Toast(toastEl, {
                        animation: true,
                        autohide: true,
                        delay: 3000
                    });
                    toast.show();

                    // Réinitialiser le formulaire
                    document.getElementById('create-user-form').reset();
                } else {
                    // Gestion des erreurs
                    var toastEl = document.getElementById('successToast');
                    var toastBody = document.getElementById('toastMessage');
                    toastBody.innerHTML = `
                        <div class="d-flex align-items-center text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Une erreur est survenue.
                        </div>
                    `;
                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            };
            xhr.send(formData);
        });
    </script>
</body>
</html>