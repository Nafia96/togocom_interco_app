<style>
    .cost-nav .btn {
        padding: .45rem .7rem;
        font-size: .9rem;
        border-radius: .35rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .cost-nav .cost-nav-active {
        background: #133272;
        color: #ffd100;
        border-color: #133272;
        font-weight: 600;
    }
</style>

<nav class="cost-nav d-flex align-items-center" aria-label="Navigation des coûts">
    <a href="{{ route('kpi.cout-carrier') }}" class="btn btn-sm cost-nav-active" aria-current="page">
        <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="16" height="16" aria-hidden="true"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h11A1.5 1.5 0 0 1 15 2.5v11a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 13.5v-11zM3 4v2h10V4H3zm0 4v1.5h2V8H3zm3.5 0v1.5h2V8h-2zm3.5 0v1.5h3V8h-3zM3 11v2h2v-2H3zm3.5 0v2h2v-2h-2zm3.5 0v2h3v-2h-3z"/></svg>
        Coût Carrier
    </a>
</nav>
