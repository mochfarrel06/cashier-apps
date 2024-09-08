<div class="d-lg-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 mt-2 text-gray-900">{{ $title }}</h1>
    @if (isset($breadcrumbs) && count($breadcrumbs) > 0)
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 mt-2">
                @foreach ($breadcrumbs as $breadcrumb)
                    @if (!$loop->last)
                        <li class="breadcrumb-item">
                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item">{{ $breadcrumb['title'] }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif
</div>
