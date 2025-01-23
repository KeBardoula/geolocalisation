document.addEventListener('DOMContentLoaded', function() {
    const query = 'votre_recherche';
    fetch('api.php?component=backoffice&action=search&query=' + encodeURIComponent(query))
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.text(); // Utilisez .text() pour voir la réponse brute
    })
    .then(data => {
        console.log("Réponse brute du serveur:", data); // Affichez la réponse brute
        try {
            const jsonData = JSON.parse(data); // Essayez de parser la réponse en JSON
            if (typeof jsonData === 'object' && jsonData !== null) {
                console.log("Réponse JSON parsée:", jsonData);
            } else {
                console.error('Réponse invalide:', jsonData);
            }
        } catch (error) {
            console.error('Erreur lors du parsing JSON:', error);
            console.log("Réponse brute du serveur:", data); // Affichez la réponse brute
        }
    })
    .catch(error => {
        console.error('Erreur AJAX:', error);
    });
});