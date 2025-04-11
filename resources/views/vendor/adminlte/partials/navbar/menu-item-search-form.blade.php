<li class="nav-item">

    {{-- Search toggle button --}}
    <a class="nav-link search-toggle-btn" data-widget="navbar-search" href="#" role="button">
        <i class="fas fa-search"></i>
    </a>

    {{-- Search bar --}}
    <div class="navbar-search-block">
        <form class="form-inline" action="{{ $item['href'] }}" method="{{ $item['method'] }}">
            {{ csrf_field() }}

            <div class="input-group search-form-container">

                {{-- Search input --}}
                <input class="form-control form-control-navbar search-input-field" type="search"
                    @isset($item['id']) id="{{ $item['id'] }}" @endisset
                    name="{{ $item['input_name'] }}"
                    placeholder="{{ $item['text'] }}"
                    aria-label="{{ $item['text'] }}">

                {{-- Search buttons --}}
                <div class="input-group-append">
                    <button class="btn btn-navbar search-submit-btn" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-navbar search-close-btn" type="button" data-widget="navbar-search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

            </div>
        </form>
    </div>

</li>

<style>
    .search-toggle-btn {
        transition: all 0.3s ease;
    }
    
    .search-toggle-btn:hover {
        transform: scale(1.1);
        color: #28a745;
    }
    
    .navbar-search-block {
        transition: all 0.3s ease;
    }
    
    .search-form-container {
        position: relative;
    }
    
    .search-input-field {
        border-radius: 20px 0 0 20px;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }
    
    .search-input-field:focus {
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        border-color: #28a745;
    }
    
    .search-submit-btn, .search-close-btn {
        transition: all 0.3s ease;
    }
    
    .search-submit-btn:hover, .search-close-btn:hover {
        background-color: #28a745;
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation pour l'ouverture de la recherche
        document.querySelectorAll('.search-toggle-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                setTimeout(function() {
                    document.querySelector('.search-input-field').focus();
                }, 100);
            });
        });
        
        // Animation pour la saisie
        document.querySelectorAll('.search-input-field').forEach(function(input) {
            input.addEventListener('keyup', function() {
                if (this.value.length > 0) {
                    document.querySelector('.search-submit-btn').classList.add('animated-pulse');
                } else {
                    document.querySelector('.search-submit-btn').classList.remove('animated-pulse');
                }
            });
        });
    });
    
    // Classe pour l'animation pulse
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            
            .animated-pulse {
                animation: pulse 1.5s infinite;
            }
        </style>
    `);
</script>
