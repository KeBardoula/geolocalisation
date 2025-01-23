<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression des Points de Vente</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Polices et icônes -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/delete_pdv.css">
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
                    <i class="bi bi-trash me-3"></i>Suppression des Points de Vente
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
                                <th>Groupe</th>
                                <th>SIRET</th>
                                <th>Responsable</th>
                            </tr>
                        </thead>
                        <tbody id="pdv-list">
                            <?php foreach ($pdvs as $pdv): ?>
                                <tr ondblclick="confirmDelete(<?php echo htmlspecialchars($pdv['id']); ?>)">
                                    <td><?php echo htmlspecialchars($pdv['id']); ?></td>
                                    <td><?php echo htmlspecialchars($pdv['name']); ?></td>
                                    <td><?php echo htmlspecialchars($pdv['groups']); ?></td>
                                    <td><?php echo htmlspecialchars($pdv['siret']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($pdv['manager_last_name'] . ' ' . $pdv['manager_first_name']); ?>
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
        function confirmDelete(pdvId) {
            const confirmDelete = window.confirm(`Voulez-vous vraiment supprimer le point de vente avec l'ID ${pdvId} ?`);
            if (confirmDelete) {
                // Créer un formulaire pour soumettre la demande de suppression
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?component=delete_pdv'; // Assurez-vous que le chemin est correct

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_pdv_id';
                input.value = pdvId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>