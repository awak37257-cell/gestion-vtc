<aside class="admin-sidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}" aria-label="TIHE'S Group, tableau de bord">
        <img class="sidebar-brand-logo" src="{{ asset('images/logo.png') }}" alt="Logo TIHE'S Group">
        <span class="sidebar-brand-name">TIHE'S Group <small>Admin</small></span>
    </a>
    <nav class="sidebar-nav" aria-label="Navigation recruteur">
        <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="fa-solid fa-chart-line" aria-hidden="true"></i><span>Tableau de bord</span>
        </a>
        <a class="sidebar-link {{ request()->routeIs('admin.candidatures.*') ? 'active' : '' }}" href="{{ route('admin.candidatures.index') }}">
            <i class="fa-solid fa-briefcase" aria-hidden="true"></i><span>Candidatures</span>
            @php $pendingCount = \App\Models\Candidature::whereIn('statut', ['en_attente', 'en_cours_examen'])->count(); @endphp
            @if($pendingCount > 0)
                <span class="sidebar-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a class="sidebar-link" href="{{ route('candidature.create') }}" target="_blank" rel="noopener">
            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i><span>Formulaire public</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="sidebar-logout" type="submit">
                <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i><span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>