<div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="{{ $icon }}"></i> {{ $title }}
    </h6>
    @if ($addRoute)
        <a href="{{ route($addRoute) }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-1">Tambah
            Data</a>
    @endif
</div>
