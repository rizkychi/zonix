<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ Vite::asset('resources/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>

            <ul class="navbar-nav" id="navbar-nav">
                @foreach ($sidebarMenu as $item)

                    {{-- SECTION TITLE --}}
                    @if ($item->type === 'title')
                        <li class="menu-title">
                            @if ($item->icon)<i class="{{ $item->icon }}"></i>@endif
                            <span>{{ $item->title }}</span>
                        </li>

                    {{-- MENU ITEM --}}
                    @else
                        @php
                            $hasChildren  = collect($item->children)->isNotEmpty();
                            $collapseId   = 'sidebarMenu' . $item->id;
                            $isActive     = $item->isActive();
                            $parentActive = $hasChildren && $item->hasActiveChild();
                        @endphp

                        <li class="nav-item">
                            @if ($hasChildren)
                                <a class="nav-link menu-link {{ $parentActive ? '' : 'collapsed' }}"
                                   href="#{{ $collapseId }}"
                                   data-bs-toggle="collapse" role="button"
                                   aria-expanded="{{ $parentActive ? 'true' : 'false' }}"
                                   aria-controls="{{ $collapseId }}">
                                    @if ($item->icon)<i class="{{ $item->icon }}"></i>@endif
                                    <span>{{ $item->title }}</span>
                                    @if ($item->badge_text)
                                        <span class="badge {{ $item->badge_class ?? 'bg-success' }} ms-auto">
                                            {{ $item->badge_text }}
                                        </span>
                                    @endif
                                </a>
                                <div class="collapse menu-dropdown {{ $parentActive ? 'show' : '' }}"
                                     id="{{ $collapseId }}">
                                    <ul class="nav nav-sm flex-column">
                                        @foreach ($item->children as $child)
                                            @php
                                                $childHasKids   = $child->children->isNotEmpty();
                                                $childId        = 'sidebarMenu' . $child->id;
                                                $childActive    = $child->isActive();
                                                $childExpanded  = $childHasKids && $child->hasActiveChild();
                                            @endphp
                                            <li class="nav-item">
                                                @if ($childHasKids)
                                                    <a href="#{{ $childId }}"
                                                       class="nav-link {{ $childExpanded ? '' : 'collapsed' }}"
                                                       data-bs-toggle="collapse" role="button"
                                                       aria-expanded="{{ $childExpanded ? 'true' : 'false' }}"
                                                       aria-controls="{{ $childId }}">
                                                       @if ($child->icon)<i class="{{ $child->icon }}"></i>@endif
                                                        {{ $child->title }}
                                                        @if ($child->badge_text)
                                                            <span class="badge {{ $child->badge_class ?? 'bg-success' }} ms-auto">
                                                                {{ $child->badge_text }}
                                                            </span>
                                                        @endif
                                                    </a>
                                                    <div class="collapse menu-dropdown {{ $childExpanded ? 'show' : '' }}"
                                                         id="{{ $childId }}">
                                                        <ul class="nav nav-sm flex-column">
                                                            @foreach ($child->children as $grandchild)
                                                                <li class="nav-item">
                                                                    <a href="{{ $grandchild->href }}"
                                                                       class="nav-link {{ $grandchild->isActive() ? 'active' : '' }}"
                                                                       @if ($grandchild->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                                                       @if ($grandchild->icon)<i class="{{ $grandchild->icon }}"></i>@endif 
                                                                       {{ $grandchild->title }}
                                                                        @if ($grandchild->badge_text)
                                                                            <span class="badge {{ $grandchild->badge_class ?? 'bg-success' }}">{{ $grandchild->badge_text }}</span>
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @else
                                                    <a href="{{ $child->href }}"
                                                       class="nav-link {{ $childActive ? 'active' : '' }}"
                                                       @if ($child->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                                        @if ($child->icon)<i class="{{ $child->icon }}"></i>@endif
                                                        {{ $child->title }}
                                                        @if ($child->badge_text)
                                                            <span class="badge {{ $child->badge_class ?? 'bg-success' }}">{{ $child->badge_text }}</span>
                                                        @endif
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a class="nav-link menu-link {{ $isActive ? 'active' : '' }}"
                                   href="{{ $item->href }}"
                                   @if ($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                                    @if ($item->icon)<i class="{{ $item->icon }}"></i>@endif
                                    <span>{{ $item->title }}</span>
                                    @if ($item->badge_text)
                                        <span class="badge {{ $item->badge_class ?? 'bg-success' }}">{{ $item->badge_text }}</span>
                                    @endif
                                </a>
                            @endif
                        </li>
                    @endif

                @endforeach
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>