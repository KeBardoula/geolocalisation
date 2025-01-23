<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Utilisateurs</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Polices et icônes -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="public/css/list_user.css">
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
                    <i class="bi bi-people-fill me-3"></i>Liste des Utilisateurs
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
                            placeholder="Rechercher un utilisateur..."
                        >
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover table-cyberpunk">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-list">
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge <?= $user['rule'] == 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                            <?php echo htmlspecialchars($user['rule']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button 
                                                class="btn btn-sm btn-outline-info details-btn" 
                                                title="Détails" 
                                                data-user-id="<?php echo $user['id']; ?>"
                                                data-last-name="<?php echo htmlspecialchars($user['last_name']); ?>"
                                                data-first-name="<?php echo htmlspecialchars($user['first_name']); ?>"
                                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                                data-rule="<?php echo htmlspecialchars($user['rule']); ?>"
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

    <!-- Modal Détails Utilisateur -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content cyberpunk-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsModalLabel">
                        <i class="bi bi-person-badge me-2 text-primary"></i>
                        Détails de l'Utilisateur
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>ID</strong>
                                <p id="modal-user-id" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Rôle</strong>
                                <p id="modal-user-rule" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Nom</strong>
                                <p id="modal-last-name" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cyberpunk-card">
                                <strong>Prénom</strong>
                                <p id="modal-first-name" class="neon-text"></p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cyberpunk-card">
                                <strong>Email</strong>
                                <p id="modal-email" class="neon-text"></p>
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
                const rows = document.querySelectorAll('#users-list tr');
                
                rows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    const rowText = Array.from(cells)
                        .map(cell => cell.textContent.toLowerCase())
                        .join(' ');
                    
                    row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                });
            });

            // Gestion de la modal des détails utilisateur
            const detailsBtns = document.querySelectorAll('.details-btn');
            const userDetailsModal = new bootstrap.Modal(document.getElementById('userDetailsModal'));

            detailsBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Récupérer les données de l'utilisateur à partir des attributs data
                    const userId = this.getAttribute('data-user-id');
                    const lastName = this.getAttribute('data-last-name');
                    const firstName = this.getAttribute('data-first-name');
                    const email = this.getAttribute('data-email');
                    const rule = this.getAttribute('data-rule');

                    // Mettre à jour les éléments de la modal
                    document.getElementById('modal-user-id').textContent = userId;
                    document.getElementById('modal-last-name').textContent = lastName;
                    document.getElementById('modal-first-name').textContent = firstName;
                    document.getElementById('modal-email').textContent = email;
                    document.getElementById('modal-user-rule').textContent = rule;

                    // Afficher la modal
                    userDetailsModal.show();
                });
            });
        });
    </script>
</body>
</html>