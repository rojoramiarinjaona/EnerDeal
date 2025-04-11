<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EnerDEAL - Échanges d'énergie renouvelable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --primary: #0ea5e9;
            --secondary: #06b6d4;
            --accent: #10b981;
            --dark: #0f172a;
            --light: #f1f5f9;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--dark);
            overflow-x: hidden;
            background-color: #fff;
        }

        .btn {
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }

        .btn-outline-light {
            border-color: #fff;
            color: #fff;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: #fff;
            transform: translateY(-3px);
        }

        .navbar {
            padding: 1rem 0;
            transition: all 0.3s ease;
            background-color: transparent;
        }

        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .nav-link {
            font-weight: 500;
            color: #fff;
            margin: 0 0.5rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .scrolled .nav-link {
            color: var(--dark);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: var(--primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 80%;
        }

        .navbar-toggler {
            border: none;
            padding: 0;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .hamburger {
            width: 30px;
            height: 20px;
            position: relative;
            display: inline-block;
        }

        .hamburger span {
            display: block;
            position: absolute;
            height: 2px;
            width: 100%;
            background: #fff;
            border-radius: 9px;
            opacity: 1;
            left: 0;
            transform: rotate(0deg);
            transition: .3s ease-in-out;
        }

        .scrolled .hamburger span {
            background: var(--dark);
        }

        .hamburger span:nth-child(1) {
            top: 0px;
        }

        .hamburger span:nth-child(2), .hamburger span:nth-child(3) {
            top: 9px;
        }

        .hamburger span:nth-child(4) {
            top: 18px;
        }

        .navbar-toggler.active .hamburger span:nth-child(1) {
            top: 9px;
            width: 0%;
            left: 50%;
        }

        .navbar-toggler.active .hamburger span:nth-child(2) {
            transform: rotate(45deg);
        }

        .navbar-toggler.active .hamburger span:nth-child(3) {
            transform: rotate(-45deg);
        }

        .navbar-toggler.active .hamburger span:nth-child(4) {
            top: 9px;
            width: 0%;
            left: 50%;
        }

        .hero-section {
            position: relative;
            padding: 12rem 0 10rem;
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: #fff;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%23ffffff" fill-opacity="0.05" d="M0,64L48,80C96,96,192,128,288,122.7C384,117,480,75,576,80C672,85,768,139,864,138.7C960,139,1056,85,1152,69.3C1248,53,1344,75,1392,85.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"%3E%3C/path%3E%3C/svg%3E');
            background-size: cover;
            background-position: center bottom;
            opacity: 0.8;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 300;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            transform: perspective(1000px) rotateY(-10deg) rotateX(5deg);
            transition: all 0.5s ease;
        }

        .hero-image img:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        .card-feature {
            background-color: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: none;
        }

        .card-feature::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            opacity: 0;
            z-index: -1;
            transition: all 0.3s ease;
        }

        .card-feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card-feature:hover::before {
            opacity: 0.05;
        }

        .feature-icon {
            font-size: 2.5rem;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .card-feature:hover .feature-icon {
            transform: scale(1.1);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            font-weight: 300;
            max-width: 700px;
            margin: 0 auto 3rem;
            color: #64748b;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <span class="text-success">Ener</span><span class="text-primary">DEAL</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#accueil">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#comment-ca-marche">Comment ça marche</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#avantages">Avantages</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="#temoignages">Témoignages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    @if (app('router')->has('login'))
                        @auth
                            <li class="nav-item ms-3">
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Tableau de bord</a>
                            </li>
                        @else
                            <li class="nav-item ms-3">
                                <a href="{{ url('login') }}" class="btn btn-outline-success">Connexion</a>
                            </li>
                            @if (app('router')->has('register'))
                                <li class="nav-item ms-2">
                                    <a href="{{ url('register') }}" class="btn btn-primary">Inscription</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <section id="accueil" class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="hero-title">Révolutionnez votre façon de consommer l'énergie</h1>
                    <p class="hero-subtitle">Première plateforme française d'échange d'énergie renouvelable entre particuliers. Simple, écologique et économique.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ url('register') }}" class="btn btn-primary">Commencer maintenant</a>
                        <a href="#comment-ca-marche" class="btn btn-outline-light">Découvrir comment ça marche</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-delay="300">
                    <div class="hero-image">
                        <img src="/img/hero-image.png" alt="EnerDEAL Platform" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="comment-ca-marche" class="py-5 mt-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Comment ça marche ?</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
                    En trois étapes simples, commencez à échanger de l'énergie renouvelable et contribuez à un avenir plus vert.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-feature text-center">
                        <div class="feature-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="feature-title">Inscrivez-vous</h3>
                        <p>Créez votre compte gratuitement et complétez votre profil en quelques minutes. Choisissez si vous êtes producteur, consommateur ou les deux.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-feature text-center">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3 class="feature-title">Proposez ou achetez</h3>
                        <p>Mettez en vente votre surplus d'énergie ou explorez le catalogue des offres disponibles selon vos besoins et préférences.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-feature text-center">
                        <div class="feature-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h3 class="feature-title">Signez et économisez</h3>
                        <p>Signez votre contrat électroniquement et commencez immédiatement à économiser tout en soutenant la transition énergétique.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="avantages" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Pourquoi choisir EnerDEAL ?</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Notre plateforme offre de nombreux avantages tant sur le plan économique qu'écologique.
                </p>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-5 mb-lg-0" data-aos="fade-left">
                    <img src="/img/advantages.jpg" alt="Avantages EnerDEAL" class="img-fluid">
                </div>
                
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-4">
                            <div class="bg-primary rounded-circle p-3 text-white" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="h4 mb-3">Économique</h3>
                            <p class="text-muted">Économisez jusqu'à 30% sur vos factures d'électricité en achetant directement auprès d'autres particuliers sans intermédiaire.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-4">
                            <div class="bg-success rounded-circle p-3 text-white" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-leaf"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="h4 mb-3">Écologique</h3>
                            <p class="text-muted">Réduisez votre empreinte carbone en favorisant la consommation d'énergies 100% renouvelables et locales.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-4">
                            <div class="bg-info rounded-circle p-3 text-white" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="h4 mb-3">Sécurisé</h3>
                            <p class="text-muted">Profitez d'une plateforme entièrement sécurisée avec des contrats standardisés et des paiements protégés.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="me-4">
                            <div class="bg-warning rounded-circle p-3 text-white" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="h4 mb-3">Local</h3>
                            <p class="text-muted">Soutenez les producteurs locaux et participez activement à l'économie circulaire de votre région.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary text-white" style="background: linear-gradient(45deg, var(--primary), var(--secondary));">
        <div class="container py-5 position-relative" style="z-index: 1;">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="h1 mb-4">Prêt à rejoindre la révolution énergétique ?</h2>
                    <p class="lead mb-4">Inscrivez-vous gratuitement et commencez à échanger de l'énergie verte dès aujourd'hui !</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ url('register') }}" class="btn btn-light">Créer un compte</a>
                        <a href="#contact" class="btn btn-outline-light">Nous contacter</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <img src="/img/renewable-energy.svg" alt="Renewable Energy" class="img-fluid" style="max-height: 300px;">
                </div>
            </div>
        </div>
    </section>

    <section id="temoignages" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Ce que disent nos utilisateurs</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Découvrez les expériences des producteurs et consommateurs qui utilisent notre plateforme.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 p-4 rounded-4">
                        <div class="d-flex mb-4">
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="card-text mb-4">"Grâce à EnerDEAL, j'ai réduit ma facture d'électricité de 25% et je valorise enfin l'énergie produite par mes panneaux solaires. Le processus est simple et transparent."</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://i.pravatar.cc/150?img=1" alt="Sophie M." class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h5 class="mb-0 h6">Sophie M.</h5>
                                <p class="text-muted mb-0 small">Particulier, Nantes</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 p-4 rounded-4">
                        <div class="d-flex mb-4">
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <p class="card-text mb-4">"En tant que petite entreprise, nous avons considérablement réduit nos coûts énergétiques tout en soutenant les producteurs locaux. Une vraie révolution pour notre business model !"</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://i.pravatar.cc/150?img=11" alt="Thomas D." class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h5 class="mb-0 h6">Thomas D.</h5>
                                <p class="text-muted mb-0 small">Entrepreneur, Lyon</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="500">
                    <div class="card border-0 shadow-sm h-100 p-4 rounded-4">
                        <div class="d-flex mb-4">
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <p class="card-text mb-4">"Notre commune a pu mettre en place une vraie politique énergétique locale grâce à EnerDEAL. Les citoyens sont enthousiastes et les économies sont au rendez-vous."</p>
                        <div class="d-flex align-items-center mt-auto">
                            <img src="https://i.pravatar.cc/150?img=15" alt="Claire B." class="rounded-circle me-3" width="50" height="50">
                            <div>
                                <h5 class="mb-0 h6">Claire B.</h5>
                                <p class="text-muted mb-0 small">Maire, Bordeaux</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="#" class="btn btn-outline-primary rounded-pill">Voir plus de témoignages</a>
            </div>
        </div>
    </section>

    <section id="statistiques" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm text-center p-4 rounded-4">
                        <h3 class="display-5 fw-bold text-primary counter-value" data-target="3500">0</h3>
                        <p class="text-muted mb-0">Utilisateurs actifs</p>
                    </div>
                </div>
                
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm text-center p-4 rounded-4">
                        <h3 class="display-5 fw-bold text-primary counter-value" data-target="12000">0</h3>
                        <p class="text-muted mb-0">Transactions réalisées</p>
                    </div>
                </div>
                
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="500">
                    <div class="card border-0 shadow-sm text-center p-4 rounded-4">
                        <h3 class="display-5 fw-bold text-primary counter-value" data-target="8500000">0</h3>
                        <p class="text-muted mb-0">kWh échangés</p>
                    </div>
                </div>
                
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="700">
                    <div class="card border-0 shadow-sm text-center p-4 rounded-4">
                        <h3 class="display-5 fw-bold text-primary counter-value" data-target="92">0</h3>
                        <p class="text-muted mb-0">% clients satisfaits</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Contactez-nous</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Une question ? Besoin d'aide ? Notre équipe est à votre disposition.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <h3 class="h4 mb-4">Informations de contact</h3>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 bg-primary text-white rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="mb-0">123 Avenue de l'Énergie, 75001 Paris</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 bg-primary text-white rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="mb-0">+33 (0)1 23 45 67 89</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 bg-primary text-white rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="mb-0">contact@enerdeal.fr</p>
                            </div>
                        </div>
                        
                        <h4 class="h5 mt-4 mb-3">Suivez-nous</h4>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-outline-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" class="form-control form-control-lg rounded-3" id="name" placeholder="Votre nom" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-lg rounded-3" id="email" placeholder="votre@email.com" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control form-control-lg rounded-3" id="phone" placeholder="Votre téléphone">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Sujet</label>
                                    <select class="form-select form-select-lg rounded-3" id="subject" required>
                                        <option value="" selected disabled>Sélectionnez un sujet</option>
                                        <option value="question">Question générale</option>
                                        <option value="support">Support technique</option>
                                        <option value="partnership">Partenariat</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control form-control-lg rounded-3" id="message" rows="5" placeholder="Votre message" required></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-3">Envoyer le message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-right">
                    <h4 class="h3 mb-4">
                        <span class="text-white">Ener</span><span class="text-primary">DEAL</span>
                    </h4>
                    <p class="mb-4">La première plateforme française d'échange d'énergie renouvelable entre particuliers, pour une transition énergétique participative et économique.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="mb-4">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#accueil" class="text-white text-decoration-none">Accueil</a></li>
                        <li class="mb-2"><a href="#comment-ca-marche" class="text-white text-decoration-none">Comment ça marche</a></li>
                        <li class="mb-2"><a href="#avantages" class="text-white text-decoration-none">Avantages</a></li>
                        <li class="mb-2"><a href="#energies" class="text-white text-decoration-none">Énergies</a></li>
                        <li class="mb-2"><a href="#temoignages" class="text-white text-decoration-none">Témoignages</a></li>
                        <li class="mb-2"><a href="#contact" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="mb-4">Ressources</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Guides</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Tutoriels</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Centre d'aide</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <h5 class="mb-4">Newsletter</h5>
                    <p class="mb-4">Inscrivez-vous pour recevoir les dernières actualités et les meilleures offres.</p>
                    <form class="mb-4">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Votre adresse email" required>
                            <button class="btn btn-primary" type="submit">S'inscrire</button>
                        </div>
                    </form>
                    <p class="small text-muted">En vous inscrivant, vous acceptez notre politique de confidentialité.</p>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="mb-0">&copy; {{ date('Y') }} EnerDEAL. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#" class="text-white text-decoration-none small">Mentions légales</a></li>
                        <li class="list-inline-item ms-3"><a href="#" class="text-white text-decoration-none small">Politique de confidentialité</a></li>
                        <li class="list-inline-item ms-3"><a href="#" class="text-white text-decoration-none small">CGU</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser AOS (Animate On Scroll)
            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });
            
            // Navigation active au scroll
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.nav-link');
            
            window.addEventListener('scroll', function() {
                let current = '';
                
                // Navbar change de couleur au scroll
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                
                // Navigation active
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    
                    if (pageYOffset >= sectionTop - 100) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').substring(1) === current) {
                        link.classList.add('active');
                    }
                });
                
                // Animation des compteurs
                const counters = document.querySelectorAll('.counter-value');
                counters.forEach(counter => {
                    // Vérifier si le compteur est visible
                    const position = counter.getBoundingClientRect();
                    
                    if(position.top < window.innerHeight && position.bottom > 0 && !counter.classList.contains('counted')) {
                        counter.classList.add('counted');
                        const target = parseInt(counter.getAttribute('data-target'));
                        const duration = 2000; // durée de l'animation en ms
                        const increment = target / (duration / 16); // 16ms = ~60fps
                        let currentCount = 0;
                        
                        const updateCount = () => {
                            currentCount += increment;
                            
                            // Formater les grands nombres
                            let displayValue;
                            if (target >= 1000000) {
                                displayValue = (Math.min(currentCount, target) / 1000000).toFixed(1) + 'M';
                            } else if (target >= 1000) {
                                displayValue = (Math.min(currentCount, target) / 1000).toFixed(0) + 'K';
                            } else {
                                displayValue = Math.floor(Math.min(currentCount, target));
                            }
                            
                            // Ajouter un "%" pour le taux de satisfaction
                            if (target === 92) {
                                displayValue += '%';
                            }
                            
                            counter.textContent = displayValue;
                            
                            if (currentCount < target) {
                                requestAnimationFrame(updateCount);
                            }
                        };
                        
                        updateCount();
                    }
                });
            });
            
            // Animation du hamburger menu
            const navbarToggler = document.querySelector('.navbar-toggler');
            navbarToggler.addEventListener('click', function() {
                this.classList.toggle('active');
            });
            
            // Smooth scroll pour les liens d'ancrage
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        const headerOffset = 80;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Fermer le menu hamburger si ouvert
                        const navbarCollapse = document.querySelector('.navbar-collapse');
                        if (navbarCollapse.classList.contains('show')) {
                            navbarToggler.click();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
</html>
</html>