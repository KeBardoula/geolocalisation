<!DOCTYPE html>
<html>
<head>
    <title>Mise à Jour Utilisateur</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/update_user.css">
</head>
<body class="container-fluid">
    <div class="row">
        <div class="col-12">
            <a href="index.php?component=backoffice" class="back-button">Retour</a>
            <h1 class="text-center my-4">Recherche et Mise à Jour Utilisateur</h1>

            <?php if (!empty($message)): ?>
                <div id="messageContainer" class="alert <?php echo $messageType === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" class="search-container mb-3" id="searchForm">
                <input type="hidden" name="action" value="search">
                <div class="input-group">
                    <input type="text" name="search" id="searchInput" 
                           class="form-control" 
                           placeholder="Rechercher un utilisateur" 
                           value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                </div>
            </form>

            <form method="post" id="updateForm">
                <input type="hidden" name="action" value="update_field">
                <input type="hidden" name="user_id" id="user_id_input">
                <input type="hidden" name="field" id="field_input">
                <input type="hidden" name="value" id="value_input">
            </form>

            <div class="table-responsive">
                <table id="userTable" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Mot de passe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucun utilisateur trouvé</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td class="editable-cell" 
                                        data-user-id="<?php echo $user['id']; ?>" 
                                        data-field="last_name">
                                        <?php echo htmlspecialchars($user['last_name']); ?>
                                    </td>
                                    <td class="editable-cell" 
                                        data-user-id="<?php echo $user['id']; ?>" 
                                        data-field="first_name">
                                        <?php echo htmlspecialchars($user['first_name']); ?>
                                    </td>
                                    <td class="editable-cell" 
                                        data-user-id="<?php echo $user['id']; ?>" 
                                        data-field="email">
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </td>
                                    <td class="editable-cell" 
                                        data-user-id="<?php echo $user['id']; ?>" 
                                        data-field="rule">
                                        <?php echo htmlspecialchars($user['rule']); ?>
                                    </td>
                                    <td class="editable-cell" 
                                        data-user-id="<?php echo $user['id']; ?>" 
                                        data-field="password">
                                        ********
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('userTable');
            const updateForm = document.getElementById('updateForm');
            const userIdInput = document.getElementById('user_id_input');
            const fieldInput = document.getElementById('field_input');
            const valueInput = document.getElementById('value_input');
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                // Annuler le timeout précédent
                clearTimeout(searchTimeout);

                // Nouveau timeout pour soumettre le formulaire après un délai
                searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 400); // 400 millisecondes (0,4 seconde)
            });
            
            let lastClickTime = 0;
            let lastClickedCell = null;

            // Fonction pour soumettre la modification
            function submitModification(cell, input, userId, field, currentValue) {
                let newValue, passwordConfirm;

                if (field === 'password') {
                    // Sélection des inputs de mot de passe
                    const passwordInputs = input.querySelectorAll('input');
                    const passwordInput = passwordInputs[0];
                    const confirmInput = passwordInputs[1];
                    
                    newValue = passwordInput.value.trim();
                    passwordConfirm = confirmInput.value.trim();

                    // Vérification que TOUS les champs sont remplis
                    if (newValue === '' || passwordConfirm === '') {
                        // Si un seul des champs est vide, ne rien faire
                        return false;
                    }

                    // Validation côté client
                    if (newValue !== passwordConfirm) {
                        // Vider les champs
                        passwordInput.value = '';
                        confirmInput.value = '';
                        
                        // Ajouter une classe d'erreur
                        passwordInput.classList.add('error');
                        confirmInput.classList.add('error');
                        
                        // Message d'erreur
                        alert('Les mots de passe ne correspondent pas');
                        
                        // Redonner le focus au premier champ
                        passwordInput.focus();
                        
                        return false;
                    }

                    if (newValue.length < 8) {
                        // Vider les champs
                        passwordInput.value = '';
                        confirmInput.value = '';
                        
                        // Ajouter une classe d'erreur
                        passwordInput.classList.add('error');
                        confirmInput.classList.add('error');
                        
                        // Message d'erreur
                        alert('Le mot de passe doit contenir au moins 8 caractères');
                        
                        // Redonner le focus au premier champ
                        passwordInput.focus();
                        
                        return false;
                    }
                } else {
                    newValue = input.value.trim();
                }
                
                // Vérifier si la valeur a changé
                if (newValue !== currentValue) {
                    // Remplir le formulaire caché
                    userIdInput.value = userId;
                    fieldInput.value = field;
                    valueInput.value = newValue;
                    
                    // Ajout du champ de confirmation de mot de passe si nécessaire
                    if (field === 'password') {
                        const passwordConfirmInput = document.createElement('input');
                        passwordConfirmInput.type = 'hidden';
                        passwordConfirmInput.name = 'password_confirm';
                        passwordConfirmInput.value = passwordConfirm;
                        updateForm.appendChild(passwordConfirmInput);
                    }
                    
                    // Soumettre le formulaire
                    updateForm.submit();
                    return true;
                } else {
                    // Restaurer la valeur originale
                    cell.innerHTML = currentValue;
                    return false;
                }
            }

            // Fonction pour créer un champ de saisie
            function createEditInput(cell, currentValue, field, userId) {
                // Création de l'input
                const input = field === 'rule' 
                    ? createRoleSelect(currentValue) 
                    : (field === 'password' 
                        ? createPasswordInputs(currentValue) 
                        : createTextInput(currentValue));

                // Gestion de la soumission
                function submit(event) {
                    // Vérifier si l'événement est un blur ou une touche Entrée
                    if (event.type === 'blur' || (event.type === 'keydown' && event.key === 'Enter')) {
                        const success = submitModification(cell, input, userId, field, currentValue);
                        if (success) {
                            return;
                        }
                    }
                }

                // Ajout des écouteurs d'événements
                if (field === 'password') {
                    const passwordInputs = input.querySelectorAll('input');
                    
                    passwordInputs.forEach(passwordInput => {
                        passwordInput.addEventListener('blur', submit);
                        passwordInput.addEventListener('keydown', submit);

                        // Ajout d'un écouteur pour vérifier les deux champs
                        passwordInput.addEventListener('input', function() {
                            const [pass1, pass2] = passwordInputs;
                            const pass1Value = pass1.value.trim();
                            const pass2Value = pass2.value.trim();

                            // Supprimer la classe d'erreur
                            pass1.classList.remove('error');
                            pass2.classList.remove('error');
                        });
                    });
                } else {
                    input.addEventListener('blur', submit);
                    input.addEventListener('keydown', submit);
                }

                cell.innerHTML = '';
                cell.appendChild(input);
                
                // Focus sur le premier input
                if (field === 'password') {
                    input.querySelector('input').focus();
                } else {
                    input.focus();
                }
            }

            // Fonction pour créer un champ de texte
            function createTextInput(value) {
                const input = document.createElement('input');
                input.type = 'text';
                input.value = value;
                input.classList.add('edit-input');
                return input;
            }

            // Fonction pour créer un sélecteur de rôle
            function createRoleSelect(currentValue) {
                const select = document.createElement('select');
                const roles = ['Admin', 'User'];
                
                roles.forEach(role => {
                    const option = document.createElement('option');
                    option.value = role.toLowerCase();
                    option.textContent = role;
                    if (role.toLowerCase() === currentValue.toLowerCase()) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
                
                select.classList.add('edit-input');
                return select;
            }

            // Fonction pour créer les champs de mot de passe
            function createPasswordInputs() {
                const container = document.createElement('div');
                container.classList.add('password-inputs');
                
                const passwordInput = document.createElement('input');
                passwordInput.type = 'password';
                passwordInput.placeholder = 'Nouveau mot de passe';
                passwordInput.classList.add('edit-input');
                passwordInput.name = 'password';

                const confirmInput = document.createElement('input');
                confirmInput.type = 'password';
                confirmInput.placeholder = 'Confirmer le mot de passe';
                confirmInput.classList.add('edit-input');
                confirmInput.name = 'password_confirm';

                // Empêcher la propagation pour permettre la saisie
                passwordInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
                confirmInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                container.appendChild(passwordInput);
                container.appendChild(confirmInput);

                return container;
            }

            // Événement de clic sur les cellules éditables
            table.addEventListener('click', function(event) {
                const cell = event.target.closest('.editable-cell');
                const currentTime = new Date().getTime();

                if (cell) {
                    // Vérifier si c'est un double-clic (moins de 300ms entre deux clics)
                    if (lastClickedCell === cell && (currentTime - lastClickTime) < 300) {
                        const userId = cell.dataset.userId;
                        const field = cell.dataset.field;
                        const currentValue = cell.textContent.trim();
                        createEditInput(cell, currentValue, field, userId);
                    }

                    // Mettre à jour le dernier clic
                    lastClickedCell = cell; lastClickTime = currentTime;
                }
            });

            // Gestion du clic en dehors pour fermer l'édition
            document.addEventListener('click', function(event) {
                const editableCell = document.querySelector('.editable-cell input, .editable-cell select');
                if (editableCell && !editableCell.closest('td').contains(event.target)) {
                    const cell = editableCell.closest('td');
                    const userId = cell.dataset.userId;
                    const field = cell.dataset.field;
                    const currentValue = cell.textContent.trim();
                    
                    // Pour les champs de mot de passe, vérifier que les deux champs sont remplis
                    if (field === 'password') {
                        const passwordInputs = cell.querySelectorAll('input');
                        const [pass1, pass2] = passwordInputs;
                        
                        if (pass1.value.trim() === '' || pass2.value.trim() === '') {
                            // Si un des champs est vide, ne rien faire
                            return;
                        }
                    }
                    
                    submitModification(cell, editableCell, userId, field, currentValue);
                }
            });

            // Style pour les champs d'erreur
            const style = document.createElement('style');
            style.textContent = `
                .edit-input.error {
                    border: 2px solid red;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>