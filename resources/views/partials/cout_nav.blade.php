<style>
    .cost-nav .btn {
        padding: .45rem .7rem;
        font-size: .9rem;
        border-radius: .35rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .cost-nav { gap: .5rem; flex-wrap: wrap; }
    .cost-nav .cost-nav-active {
        background: #133272;
        color: #ffd100;
        border-color: #133272;
        font-weight: 600;
    }
    .cost-nav .btn:not(.cost-nav-active) {
        background: #fff;
        color: #133272;
        border: 1px solid #526d9f;
        font-weight: 600;
    }
    .cost-nav .btn:not(.cost-nav-active):hover,
    .cost-nav .btn:not(.cost-nav-active):focus-visible {
        background: #e8eef8;
        color: #0b214d;
        border-color: #133272;
    }
</style>

<nav class="cost-nav d-flex align-items-center" aria-label="Navigation des coûts">
    <a href="{{ route('kpi.cout-carrier') }}" class="btn btn-sm {{ request()->routeIs('kpi.cout-carrier') ? 'cost-nav-active' : '' }}" {{ request()->routeIs('kpi.cout-carrier') ? 'aria-current=page' : '' }}>
        <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="16" height="16" aria-hidden="true"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h11A1.5 1.5 0 0 1 15 2.5v11a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 13.5v-11zM3 4v2h10V4H3zm0 4v1.5h2V8H3zm3.5 0v1.5h2V8h-2zm3.5 0v1.5h3V8h-3zM3 11v2h2v-2H3zm3.5 0v2h2v-2h-2zm3.5 0v2h3v-2h-3z"/></svg>
        Coût Carrier
    </a>
    <a href="{{ route('kpi.roaming-openings') }}" class="btn btn-sm {{ request()->routeIs('kpi.roaming-openings') ? 'cost-nav-active' : '' }}" {{ request()->routeIs('kpi.roaming-openings') ? 'aria-current=page' : '' }}>
        <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="16" height="16" aria-hidden="true"><path d="M2 2h12v2H2V2zm0 4h3v3H2V6zm4 0h8v3H6V6zm-4 4h3v3H2v-3zm4 0h8v3H6v-3z"/></svg>
        Ouvertures Roaming
    </a>
    <a href="{{ route('kpi.sim-test-roaming') }}" class="btn btn-sm {{ request()->routeIs('kpi.sim-test-roaming*') ? 'cost-nav-active' : '' }}" {{ request()->routeIs('kpi.sim-test-roaming*') ? 'aria-current=page' : '' }}>
        <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="16" height="16" aria-hidden="true"><path d="M5 1h6a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm0 2v9h6V3H5zm2 10h2v1H7v-1z"/></svg>
        Liste des SIM de test
    </a>
</nav>
