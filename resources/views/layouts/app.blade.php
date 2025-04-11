@yield('content')

<!-- Formulaire de déconnexion invisible -->
<form id="logout-form-custom" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    // Script pour gérer le bouton de déconnexion
    document.addEventListener('DOMContentLoaded', function() {
        // Pour le bouton dans la barre latérale
        const sidebarLogoutButton = document.getElementById('sidebar-logout-button');
        if (sidebarLogoutButton) {
            sidebarLogoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('logout-form-custom').submit();
                return false;
            });
        }
        
        // Pour tout autre bouton avec la classe btn-logout
        const logoutButtons = document.querySelectorAll('.btn-logout');
        logoutButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('logout-form-custom').submit();
                return false;
            });
        });
    });
</script>

</body>
</html> 