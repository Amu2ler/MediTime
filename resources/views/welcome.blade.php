<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MediTime') }} — Prenez rendez-vous en ligne</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            line-height: 1.6;
        }

        a { text-decoration: none; color: inherit; }

        /* ─── NAV ─── */
        .nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e2e8f0;
        }
        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
            font-weight: 800;
            color: #1e40af;
            flex-shrink: 0;
        }
        .nav-logo svg { width: 32px; height: 32px; }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            transition: color 0.15s;
        }
        .nav-links a:hover { color: #1e40af; }
        .nav-auth {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .btn-ghost {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #1e40af;
            border: 1.5px solid #bfdbfe;
            background: transparent;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-ghost:hover { background: #eff6ff; border-color: #93c5fd; }
        .btn-primary {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            background: #2563eb;
            border: 1.5px solid #2563eb;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; }

        /* ─── HERO ─── */
        .hero {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 40%, #2563eb 70%, #3b82f6 100%);
            padding: 80px 24px 100px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-inner {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            position: relative;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: #bfdbfe;
            font-size: 13px;
            font-weight: 500;
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 24px;
        }
        .hero-badge span {
            width: 6px; height: 6px;
            background: #34d399;
            border-radius: 50%;
            display: inline-block;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.25rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }
        .hero h1 em {
            font-style: normal;
            color: #93c5fd;
        }
        .hero-sub {
            font-size: 18px;
            color: #bfdbfe;
            max-width: 560px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        /* Search bar */
        .search-box {
            background: white;
            border-radius: 16px;
            padding: 8px;
            display: flex;
            gap: 8px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            max-width: 640px;
            margin: 0 auto 40px;
        }
        .search-box select {
            flex: 1;
            border: none;
            outline: none;
            font-size: 15px;
            font-family: inherit;
            color: #0f172a;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
        }
        .search-box select:focus { background: #eff6ff; }
        .search-box button {
            padding: 12px 28px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
            white-space: nowrap;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-box button:hover { background: #1d4ed8; }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        .hero-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
        }
        .hero-stat strong { font-size: 24px; font-weight: 800; }
        .hero-stat span { font-size: 13px; color: #93c5fd; }

        /* ─── SECTIONS ─── */
        .section {
            padding: 80px 24px;
        }
        .section-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-label {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(1.5rem, 3vw, 2.25rem);
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        .section-sub {
            font-size: 16px;
            color: #64748b;
            margin-top: 12px;
            max-width: 560px;
            line-height: 1.7;
        }

        /* ─── HOW IT WORKS ─── */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 32px;
            margin-top: 56px;
        }
        .step-card {
            background: white;
            border-radius: 20px;
            padding: 36px 28px;
            border: 1px solid #e2e8f0;
            position: relative;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .step-card:hover { box-shadow: 0 8px 32px rgba(37,99,235,0.1); transform: translateY(-2px); }
        .step-number {
            font-size: 56px;
            font-weight: 900;
            color: #eff6ff;
            line-height: 1;
            position: absolute;
            top: 20px;
            right: 24px;
        }
        .step-icon {
            width: 52px;
            height: 52px;
            background: #eff6ff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: #2563eb;
        }
        .step-icon svg { width: 26px; height: 26px; }
        .step-card h3 { font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
        .step-card p { font-size: 14px; color: #64748b; line-height: 1.6; }

        /* ─── SPECIALTIES ─── */
        .bg-light { background: white; }
        .specialties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
            margin-top: 48px;
        }
        .specialty-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            transition: all 0.15s;
            text-decoration: none;
        }
        .specialty-pill:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #1e40af;
        }
        .specialty-dot {
            width: 8px; height: 8px;
            background: #2563eb;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ─── DOCTOR CTA ─── */
        .cta-section {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            padding: 80px 24px;
        }
        .cta-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            flex-wrap: wrap;
        }
        .cta-text { flex: 1; min-width: 280px; }
        .cta-text h2 { font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 800; color: white; margin-bottom: 14px; }
        .cta-text p { font-size: 16px; color: #94a3b8; line-height: 1.7; }
        .cta-cards {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .cta-feature {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #bfdbfe;
            font-size: 14px;
            font-weight: 500;
        }
        .cta-feature svg { width: 18px; height: 18px; color: #34d399; flex-shrink: 0; }
        .btn-white {
            padding: 14px 32px;
            background: white;
            color: #1e40af;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            display: inline-block;
            transition: all 0.15s;
            flex-shrink: 0;
        }
        .btn-white:hover { background: #eff6ff; }

        /* ─── FOOTER ─── */
        .footer {
            background: #0f172a;
            padding: 40px 24px;
            border-top: 1px solid #1e293b;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .footer-logo {
            font-size: 18px;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .footer p { font-size: 13px; color: #64748b; }
        .footer-links { display: flex; gap: 20px; }
        .footer-links a { font-size: 13px; color: #64748b; transition: color 0.15s; }
        .footer-links a:hover { color: #93c5fd; }

        /* ─── AUTH LOGGED IN ─── */
        .logged-in-banner {
            background: white;
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 20px 24px;
            max-width: 540px;
            margin: 0 auto;
            text-align: left;
        }
        .logged-in-banner .greeting { font-size: 14px; color: #64748b; margin-bottom: 4px; }
        .logged-in-banner .name { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px; }
        .logged-in-banner .role-badge {
            display: inline-block;
            padding: 2px 10px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
            text-transform: capitalize;
        }
        .logged-in-links { display: flex; flex-wrap: wrap; gap: 10px; }
        .logged-in-links a {
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }
        .link-primary { background: #2563eb; color: white; }
        .link-primary:hover { background: #1d4ed8; }
        .link-secondary { background: #f1f5f9; color: #1e40af; }
        .link-secondary:hover { background: #e2e8f0; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 640px) {
            .nav-links { display: none; }
            .hero { padding: 60px 20px 80px; }
            .search-box { flex-direction: column; }
            .search-box select { background: white; }
            .hero-stats { gap: 24px; }
            .cta-inner { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- ═══ NAVBAR ═══ -->
    <nav class="nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="8" fill="#2563eb"/>
                    <path d="M16 8v16M8 16h16" stroke="white" stroke-width="3" stroke-linecap="round"/>
                </svg>
                MediTime
            </a>

            <div class="nav-links">
                <a href="{{ route('doctor.search') }}">Trouver un médecin</a>
                <a href="#comment-ca-marche">Comment ça marche</a>
                <a href="#specialites">Spécialités</a>
            </div>

            <div class="nav-auth">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">Mon espace</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">Se connecter</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Créer un compte</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- ═══ HERO ═══ -->
    <section class="hero">
        <div class="hero-inner">

            <h1>Prenez rendez-vous avec<br>le <em>bon médecin</em></h1>

            <p class="hero-sub">
                Trouvez un praticien disponible près de chez vous, consultez ses créneaux et confirmez votre rendez-vous en quelques secondes.
            </p>

            @guest
                <form class="search-box" action="{{ route('doctor.search') }}" method="GET">
                    <select name="specialty_id">
                        <option value="">Toutes les spécialités</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rechercher
                    </button>
                </form>
            @else
                <div class="logged-in-banner">
                    <p class="greeting">Bienvenue,</p>
                    <p class="name">
                        {{ auth()->user()->name }}
                        <span class="role-badge">{{ auth()->user()->role }}</span>
                    </p>
                    <div class="logged-in-links">
                        <a href="{{ route('dashboard') }}" class="link-primary">Mon tableau de bord</a>
                        @if(auth()->user()->role === 'patient')
                            <a href="{{ route('doctor.search') }}" class="link-secondary">Trouver un médecin</a>
                        @elseif(auth()->user()->role === 'doctor')
                            <a href="{{ route('slots.index') }}" class="link-secondary">Mes disponibilités</a>
                        @endif
                    </div>
                </div>
            @endauth

            @guest
            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>500+</strong>
                    <span>Médecins</span>
                </div>
                <div class="hero-stat">
                    <strong>24/7</strong>
                    <span>Disponible</span>
                </div>
                <div class="hero-stat">
                    <strong>100%</strong>
                    <span>Gratuit</span>
                </div>
                <div class="hero-stat">
                    <strong>{{ $specialties->count() }}</strong>
                    <span>Spécialités</span>
                </div>
            </div>
            @endguest
        </div>
    </section>

    <!-- ═══ COMMENT ÇA MARCHE ═══ -->
    <section class="section" id="comment-ca-marche">
        <div class="section-inner">
            <div class="section-label">Simple et rapide</div>
            <h2 class="section-title">Comment ça fonctionne ?</h2>
            <p class="section-sub">Trois étapes suffisent pour décrocher votre rendez-vous médical.</p>

            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-number">1</span>
                    <div class="step-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3>Cherchez un praticien</h3>
                    <p>Sélectionnez une spécialité médicale et trouvez les médecins disponibles près de chez vous.</p>
                </div>
                <div class="step-card">
                    <span class="step-number">2</span>
                    <div class="step-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3>Choisissez un créneau</h3>
                    <p>Consultez le calendrier du médecin et sélectionnez le créneau qui vous convient le mieux.</p>
                </div>
                <div class="step-card">
                    <span class="step-number">3</span>
                    <div class="step-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3>Confirmez votre RDV</h3>
                    <p>Indiquez votre motif de consultation, confirmez en un clic et recevez votre confirmation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ SPECIALITÉS ═══ -->
    @if($specialties->count() > 0)
    <section class="section bg-light" id="specialites">
        <div class="section-inner">
            <div class="section-label">{{ $specialties->count() }} spécialités disponibles</div>
            <h2 class="section-title">Trouvez votre spécialité</h2>
            <p class="section-sub">Médecins généralistes, spécialistes… MediTime couvre l'ensemble des disciplines médicales.</p>

            <div class="specialties-grid">
                @foreach($specialties as $specialty)
                    <a href="{{ route('doctor.search', ['specialty_id' => $specialty->id]) }}" class="specialty-pill">
                        <span class="specialty-dot"></span>
                        {{ $specialty->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- ═══ CTA MÉDECINS ═══ -->
    @guest
    <section class="cta-section">
        <div class="cta-inner">
            <div class="cta-text">
                <h2>Vous êtes médecin ?</h2>
                <p>Rejoignez MediTime et gérez vos rendez-vous en ligne. Publiez vos disponibilités, recevez des patients et suivez votre activité depuis votre tableau de bord.</p>
                <div style="display:flex; flex-direction:column; gap:10px; margin-top:20px;">
                    <div class="cta-feature">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Agenda en ligne accessible 24h/24
                    </div>
                    <div class="cta-feature">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Gestion simplifiée de vos patients
                    </div>
                    <div class="cta-feature">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Inscription 100 % gratuite
                    </div>
                </div>
            </div>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-white">
                    Rejoindre MediTime →
                </a>
            @endif
        </div>
    </section>
    @endguest

    <!-- ═══ FOOTER ═══ -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-logo">
                <svg width="24" height="24" viewBox="0 0 32 32" fill="none">
                    <rect width="32" height="32" rx="8" fill="#2563eb"/>
                    <path d="M16 8v16M8 16h16" stroke="white" stroke-width="3" stroke-linecap="round"/>
                </svg>
                MediTime
            </div>
            <p>© {{ date('Y') }} MediTime. Tous droits réservés.</p>
            <div class="footer-links">
                <a href="{{ route('doctor.search') }}">Trouver un médecin</a>
                @guest
                    <a href="{{ route('login') }}">Connexion</a>
                    <a href="{{ route('register') }}">Inscription</a>
                @endguest
            </div>
        </div>
    </footer>

</body>
</html>
