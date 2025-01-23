<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression d'Utilisateurs</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Polices et icônes -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/delete_user.css">
</head>
<body>
    <div class="container-fluid">
        <div class="position-absolute top-0 start-0 m-3">
            <a href="index.php?component=backoffice" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 cyberpunk-container">
                <h1 class="cyberpunk-title">
                    <i class="bi bi-trash me-3"></i>Suppression d'Utilisateurs
                </h1>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover table-cyberpunk">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                            </tr>
                        </thead>
                        <tbody id="users-list">
                            <?php foreach ($users as $user): ?>
                                <tr ondblclick="confirmDelete(<?php echo htmlspecialchars($user['id']); ?>)">
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($user['rule']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            const confirmDelete = window.confirm(`Voulez-vous vraiment supprimer l'utilisateur avec l'ID ${userId} ?`);
            if (confirmDelete) {
                // Créer un formulaire pour soumettre la demande de suppression
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?component=delete_user'; // Assurez-vous que le chemin est correct

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_user_id';
                input.value = userId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>