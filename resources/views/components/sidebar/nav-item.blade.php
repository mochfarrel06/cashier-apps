@if ($subItems)
    <div class="sidebar-heading">
        {{ $title }}
    </div>
    <li class="nav-item {{ $isActive() ? 'active' : '' }}">
        <a class="nav-link collapsed" data-toggle="collapse" href="" data-target="#{{ $collapseId }}"
            aria-expanded="true" aria-controls="{{ $collapseId }}">
            <i class="fas {{ $icon }}"></i>
            <span>{{ $label }}</span>
        </a>
        <div id="{{ $collapseId }}" class="collapse {{ $isActive() ? 'show' : '' }}" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">{{ $label }}</h6>
                @foreach ($subItems as $index => $subItem)
                    <a class="collapse-item {{ request()->routeIs($routes[$index]) ? 'active' : '' }}"
                        href="{{ route($subItem['route']) }}">{{ $subItem['label'] }}</a>
                @endforeach
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
@else
    <li class="nav-item {{ $isActive() ? 'active' : '' }}">
        <a class="nav-link" href="{{ route($route) }}">
            <i class="fas {{ $icon }}"></i>
            <span>{{ $label }}</span>
        </a>
    </li>
    <hr class="sidebar-divider">
@endif
