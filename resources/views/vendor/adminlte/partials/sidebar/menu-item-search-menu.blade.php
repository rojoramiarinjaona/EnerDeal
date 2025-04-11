<li>

    <div class="form-inline my-2">
        <div class="input-group sidebar-search-group" data-widget="sidebar-search" data-arrow-sign="&raquo;">

            {{-- Search input --}}
            <input class="form-control form-control-sidebar sidebar-search-input" type="search"
                @isset($item['id']) id="{{ $item['id'] }}" @endisset
                placeholder="{{ $item['text'] }}"
                aria-label="{{ $item['text'] }}">

            {{-- Search button --}}
            <div class="input-group-append">
                <button class="btn btn-sidebar sidebar-search-btn">
                    <i class="fas fa-fw fa-search sidebar-search-icon"></i>
                </button>
            </div>

        </div>
    </div>

</li>

<style>
    .sidebar-search-group {
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .sidebar-search-group:focus-within {
        box-shadow: 0 0 8px rgba(40, 167, 69, 0.4);
        transform: translateY(-2px);
    }
    
    .sidebar-search-input {
        border-radius: 20px 0 0 20px;
        transition: all 0.3s ease;
    }
    
    .sidebar-search-input:focus {
        background-color: rgba(255, 255, 255, 0.2);
    }
    
    .sidebar-search-btn {
        transition: all 0.3s ease;
        border-radius: 0 20px 20px 0;
    }
    
    .sidebar-search-btn:hover {
        background-color: rgba(40, 167, 69, 0.3);
    }
    
    .sidebar-search-icon {
        transition: all 0.3s ease;
    }
    
    .sidebar-search-btn:hover .sidebar-search-icon {
        transform: scale(1.2);
    }
    
    /* Animations pour les résultats de recherche */
    .sidebar-search-results .list-group-item {
        transition: all 0.3s ease;
    }
    
    .sidebar-search-results .list-group-item:hover {
        background-color: rgba(40, 167, 69, 0.1);
        transform: translateX(5px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animer l'icône de recherche au focus du champ
        document.querySelectorAll('.sidebar-search-input').forEach(function(input) {
            input.addEventListener('focus', function() {
                document.querySelector('.sidebar-search-icon').classList.add('animated-swing');
            });
            
            input.addEventListener('blur', function() {
                document.querySelector('.sidebar-search-icon').classList.remove('animated-swing');
            });
            
            // Sauvegarder les recherches récentes
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && this.value.trim().length > 0) {
                    let recentSearches = JSON.parse(localStorage.getItem('sidebar-recent-searches') || '[]');
                    if (!recentSearches.includes(this.value.trim())) {
                        recentSearches.unshift(this.value.trim());
                        recentSearches = recentSearches.slice(0, 5); // Garder les 5 dernières recherches
                        localStorage.setItem('sidebar-recent-searches', JSON.stringify(recentSearches));
                    }
                }
            });
        });
    });
    
    // Classe pour l'animation
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes swing {
                0% { transform: rotate(0deg); }
                25% { transform: rotate(-10deg); }
                50% { transform: rotate(10deg); }
                75% { transform: rotate(-5deg); }
                100% { transform: rotate(0deg); }
            }
            
            .animated-swing {
                animation: swing 0.5s ease;
            }
        </style>
    `);
</script>
