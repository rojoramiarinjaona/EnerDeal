// Script personnalisé pour AdminLTE

// Gestion du bouton de déconnexion
document.addEventListener('DOMContentLoaded', function () {
    const logoutButton = document.getElementById('sidebar-logout-button');

    if (logoutButton) {
        logoutButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Créer un formulaire dynamiquement
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';
            form.style.display = 'none';

            // Ajouter le token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;

            // Ajouter les éléments au DOM et soumettre
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        });
    }
}); 