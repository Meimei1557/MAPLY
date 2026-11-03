@if($paginator->hasPages())
<nav class="pagination-maply" role="navigation" aria-label="Navigasi halaman">
    <span class="page-btn is-active">{{ $paginator->currentPage() }}</span>
    <span class="page-btn is-disabled">/ {{ $paginator->lastPage() }}</span>
</nav>
@endif
