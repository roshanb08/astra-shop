<aside class="sidebar-box navbar-expand-xs border-radius-xl">
  <div class="sidebar-info">
    <div class="left">
      <ul class="list-unstyled navbar-nav">
        @php
            $global_admin_menus = ["Design", "Plugin", "Settings", "Help"];
        @endphp
        @foreach ($links as $link)
        
        @if (in_array($link['title'], $global_admin_menus) && !auth()->user()->is_global_admin)
            @continue
        @endif
        
        <li class="nav-item {{ $link['active'] ? 'active' : '' }}">
          <a target="{{ $link['blank'] ? '_blank' : '_self' }}" class="nav-link" href="{{ $link['url'] }}">
            <i class="{{ $link['icon'] }}"></i> <span>{{ $link['title'] }}</span>
          </a>
        </li>
        @endforeach
      </ul>
    </div>

    @if ($currentLink['children'] ?? [])
      <div class="right">
        <h4 class="title">{{ $currentLink['title'] }}</h4>
        <ul class="list-unstyled navbar-nav">
          @foreach ($currentLink['children'] as $link)
          <li class="nav-item {{ $link['active'] ? 'active' : '' }}">
            <a target="{{ $link['blank'] ? '_blank' : '_self' }}" class="nav-link" href="{{ $link['url'] }}">
              {{ $link['title'] }}
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    @endif
  </div>
</aside>

