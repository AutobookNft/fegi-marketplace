<div class="drawer-side">
    <!-- drawer-overlay gestisce il click fuori dalla sidebar per chiuderla su mobile -->
    <label for="main-drawer" class="drawer-overlay"></label>
    <aside class="flex flex-col min-h-screen bg-neutral text-neutral-content w-80">
        <!-- Titolo del Contesto -->
        <div class="p-6 text-2xl font-semibold border-b border-neutral-focus">
            {{ __($contextTitle) }}
        </div>

        <!-- Menu OS1 Enhanced with Modal Support -->
        <div class="flex-1 px-4 py-2 space-y-3 overflow-y-auto">
            @if (!empty($menus))
                @foreach ($menus as $key => $menu)
                    @if (empty($menu['permission']) || \Illuminate\Support\Facades\Gate::allows($menu['permission']))
                        @php
                            // Logica per determinare se il gruppo o un suo item è attivo
                            $isGroupActive = false;
                            $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();

                            if (!empty($menu['items'])) {
                                foreach ($menu['items'] as $subItem) {
                                    // OS1 Enhancement: Skip modal actions in active route detection
                                    if (!$subItem['is_modal_action'] && $currentRouteName == $subItem['route']) {
                                        $isGroupActive = true;
                                        break;
                                    }
                                }
                            } elseif (isset($menu['summary_route']) && $currentRouteName == $menu['summary_route']) {
                                $isGroupActive = true;
                            }
                        @endphp

                        @if (!empty($menu['items']))
                            <!-- Summary con sottomenù -->
                            <details class="bg-transparent collapse-arrow group collapse"
                                @if ($isGroupActive) open @endif>
                                <summary
                                    class="{{ $isGroupActive ? 'bg-primary text-primary-content shadow-sm rounded-md' : 'hover:bg-base-content hover:bg-opacity-10 rounded-md' }} focus-visible:ring-primary cursor-pointer list-none transition-colors duration-150 ease-in-out focus-visible:outline-none focus-visible:ring-2">
                                    <div class="flex items-center gap-3 px-3 py-3 text-base font-medium collapse-title">
                                        @if (!empty($menu['icon']))
                                            <span
                                                class="{{ $isGroupActive ? '' : 'opacity-60 group-hover:opacity-100 transition-opacity' }} flex-shrink-0">
                                                {!! $menu['icon'] !!}
                                            </span>
                                        @endif
                                        <span class="flex-grow truncate">{{ $menu['name'] }}</span>
                                    </div>
                                </summary>

                                <!-- OS1 Enhanced Submenu Content -->
                                <div class="pt-2 pb-1 pl-6 pr-2 space-y-1 collapse-content">
                                    @foreach ($menu['items'] as $item)
                                        @if (empty($item['permission']) || \Illuminate\Support\Facades\Gate::allows($item['permission']))
                                            @php
                                                // Only check active state for non-modal items
                                                $isItemActive =
                                                    !$item['is_modal_action'] && $currentRouteName == $item['route'];
                                            @endphp

                                            @if ($item['is_modal_action'])
                                                <!-- OS1 Modal Action Button -->
                                                <button type="button"
                                                    @if (isset($item['html_attributes'])) @foreach ($item['html_attributes'] as $attr => $value)
                                                               {{ $attr }}="{{ $value }}"
                                                           @endforeach @endif
                                                    class="hover:bg-base-content focus-visible:ring-primary flex w-full items-center justify-start gap-3 rounded-md px-3 py-2.5 text-left text-sm transition-colors duration-150 ease-in-out hover:bg-opacity-10 focus-visible:outline-none focus-visible:ring-2">
                                                    @if (!empty($item['icon']))
                                                        <span
                                                            class="flex-shrink-0 transition-opacity opacity-60 group-hover:opacity-100">
                                                            {!! $item['icon'] !!}
                                                        </span>
                                                    @else
                                                        <span class="w-5 h-5"></span>
                                                    @endif
                                                    <span class="flex-grow truncate">{{ $item['name'] }}</span>
                                                    <!-- OS1 Visual Indicator for Modal Actions -->
                                                    <span class="text-xs opacity-40">
                                                        ↗
                                                    </span>
                                                </button>
                                            @else
                                                <!-- Traditional Route Link -->
                                                <a href="{{ $item['href'] }}"
                                                    class="{{ $isItemActive ? 'bg-primary/80 text-primary-content font-semibold shadow-sm' : 'hover:bg-base-content hover:bg-opacity-10' }} flex w-full items-center justify-start gap-3 rounded-md px-3 py-2.5 text-sm transition-colors duration-150 ease-in-out">
                                                    @if (!empty($item['icon']))
                                                        <span
                                                            class="{{ $isItemActive ? '' : 'opacity-60 group-hover:opacity-100 transition-opacity' }} flex-shrink-0">
                                                            {!! $item['icon'] !!}
                                                        </span>
                                                    @else
                                                        <span class="w-5 h-5"></span>
                                                    @endif
                                                    <span class="flex-grow truncate">{{ $item['name'] }}</span>
                                                </a>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </details>
                        @else
                            <!-- Link diretto (senza sottomenu) -->
                            @if (isset($menu['is_modal_action']) && $menu['is_modal_action'])
                                <!-- OS1 Modal Action (Direct) -->
                                <button type="button"
                                    @if (isset($menu['html_attributes'])) @foreach ($menu['html_attributes'] as $attr => $value)
                                               {{ $attr }}="{{ $value }}"
                                           @endforeach @endif
                                    class="flex items-center w-full gap-3 px-3 py-3 text-base font-medium text-left list-none transition-colors duration-150 ease-in-out rounded-md hover:bg-base-content focus-visible:ring-primary hover:bg-opacity-10 focus-visible:outline-none focus-visible:ring-2">
                                    @if (!empty($menu['icon']))
                                        <span
                                            class="flex-shrink-0 transition-opacity opacity-60 group-hover:opacity-100">
                                            {!! $menu['icon'] !!}
                                        </span>
                                    @endif
                                    <span class="flex-grow truncate">{{ $menu['name'] }}</span>
                                    <span class="text-xs opacity-40">
                                        ↗
                                    </span>
                                </button>
                            @else
                                <!-- Traditional Route Link (Direct) -->
                                <a href="{{ isset($menu['summary_route']) ? route($menu['summary_route']) : '#' }}"
                                    class="{{ $isGroupActive ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-base-content hover:bg-opacity-10' }} flex list-none items-center gap-3 rounded-md px-3 py-3 text-base font-medium transition-colors duration-150 ease-in-out">
                                    @if (!empty($menu['icon']))
                                        <span
                                            class="{{ $isGroupActive ? '' : 'opacity-60 group-hover:opacity-100 transition-opacity' }} flex-shrink-0">
                                            {!! $menu['icon'] !!}
                                        </span>
                                    @endif
                                    <span class="flex-grow truncate">{{ $menu['name'] }}</span>
                                </a>
                            @endif
                        @endif

                        @if (!$loop->last)
                            <div class="my-2 border-t border-neutral-focus/20"></div>
                        @endif
                    @endif
                @endforeach
            @else
                <p class="text-center text-neutral-content opacity-60">
                    Nessun menu disponibile
                </p>
            @endif
        </div>
    </aside>
</div>
