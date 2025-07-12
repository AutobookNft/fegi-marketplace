@php
    use App\Services\Menu\ContextMenus;
    use App\Services\Menu\MenuConditionEvaluator;
    use App\Repositories\IconRepository;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Gate;

    // Determina il contesto dalla rotta corrente
    $currentRouteName = Route::currentRouteName();
    $context = explode('.', $currentRouteName)[0] ?? 'dashboard';
    $contextTitle = __('menu.' . $context);

    // Ottieni i menu per il contesto corrente
    $evaluator = new MenuConditionEvaluator();
    $iconRepo = app(IconRepository::class);
    $allMenus = ContextMenus::getMenusForContext($context);

    $menus = [];
    foreach ($allMenus as $menu) {
        $filteredItems = array_filter($menu->items, function ($item) use ($evaluator) {
            return $evaluator->shouldDisplay($item);
        });

        if (!empty($filteredItems)) {
            $menuArray = [
                'name' => $menu->name,
                'icon' => $menu->icon ? $iconRepo->getDefaultIcon($menu->icon) : null,
                'permission' => $menu->permission ?? null,
                'items' => [],
            ];

            foreach ($filteredItems as $item) {
                $menuArray['items'][] = [
                    'name' => $item->name,
                    'route' => $item->route,
                    'icon' => $item->icon ? $iconRepo->getDefaultIcon($item->icon) : null,
                    'permission' => $item->permission ?? null,
                    'is_modal_action' => $item->isModalAction ?? false,
                    'modal_action' => $item->modalAction ?? null,
                    'href' => $item->getHref(),
                    'html_attributes' => $item->getHtmlAttributes(),
                ];
            }

            $menus[] = $menuArray;
        }
    }
@endphp

<div class="drawer-side">
    <!-- drawer-overlay gestisce il click fuori dalla sidebar per chiuderla su mobile -->
    <label for="main-drawer" class="drawer-overlay"></label>
    <aside class="flex min-h-screen w-80 flex-col bg-neutral text-neutral-content">
        <!-- Titolo del Contesto -->
        <div class="border-neutral-focus border-b p-6 text-2xl font-semibold">
            {{ $contextTitle }}
        </div>

        <!-- Menu -->
        <div class="flex-1 space-y-3 overflow-y-auto px-4 py-2">
            @if (!empty($menus))
                @foreach ($menus as $key => $menu)
                    @if (empty($menu['permission']) || Gate::allows($menu['permission']))
                        @php
                            // Logica per determinare se il gruppo o un suo item è attivo
                            $isGroupActive = false;
                            $currentRouteName = Route::currentRouteName();

                            if (!empty($menu['items'])) {
                                foreach ($menu['items'] as $subItem) {
                                    if (!$subItem['is_modal_action'] && $currentRouteName == $subItem['route']) {
                                        $isGroupActive = true;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if (!empty($menu['items']))
                            <!-- Summary con sottomenù -->
                            <details class="group collapse collapse-arrow bg-transparent"
                                @if ($isGroupActive) open @endif>
                                <summary
                                    class="{{ $isGroupActive ? 'bg-primary text-primary-content shadow-sm rounded-md' : 'hover:bg-base-content hover:bg-opacity-10 rounded-md' }} cursor-pointer list-none transition-colors duration-150 ease-in-out">
                                    <div class="collapse-title flex items-center gap-3 px-3 py-3 text-base font-medium">
                                        @if (!empty($menu['icon']))
                                            <span class="flex-shrink-0">
                                                {!! $menu['icon'] !!}
                                            </span>
                                        @endif
                                        <span class="flex-grow truncate">{{ $menu['name'] }}</span>
                                    </div>
                                </summary>

                                <!-- Submenu Content -->
                                <div class="collapse-content space-y-1 pb-1 pl-6 pr-2 pt-2">
                                    @foreach ($menu['items'] as $item)
                                        @if (empty($item['permission']) || Gate::allows($item['permission']))
                                            @php
                                                $isItemActive =
                                                    !$item['is_modal_action'] && $currentRouteName == $item['route'];
                                            @endphp

                                            @if ($item['is_modal_action'])
                                                <!-- Modal Action Button -->
                                                <button type="button"
                                                    class="flex w-full items-center justify-start gap-3 rounded-md px-3 py-2.5 text-left text-sm transition-colors duration-150 ease-in-out hover:bg-base-content hover:bg-opacity-10">
                                                    @if (!empty($item['icon']))
                                                        <span class="flex-shrink-0">
                                                            {!! $item['icon'] !!}
                                                        </span>
                                                    @else
                                                        <span class="h-5 w-5"></span>
                                                    @endif
                                                    <span class="flex-grow truncate">{{ $item['name'] }}</span>
                                                    <span class="text-xs opacity-40">↗</span>
                                                </button>
                                            @else
                                                <!-- Traditional Route Link -->
                                                <a href="{{ $item['href'] }}"
                                                    class="{{ $isItemActive ? 'bg-primary/80 text-primary-content font-semibold shadow-sm' : 'hover:bg-base-content hover:bg-opacity-10' }} flex w-full items-center justify-start gap-3 rounded-md px-3 py-2.5 text-sm transition-colors duration-150 ease-in-out">
                                                    @if (!empty($item['icon']))
                                                        <span class="flex-shrink-0">
                                                            {!! $item['icon'] !!}
                                                        </span>
                                                    @else
                                                        <span class="h-5 w-5"></span>
                                                    @endif
                                                    <span class="flex-grow truncate">{{ $item['name'] }}</span>
                                                </a>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </details>
                        @endif

                        @if (!$loop->last)
                            <div class="border-neutral-focus/20 my-2 border-t"></div>
                        @endif
                    @endif
                @endforeach

                {{-- Link diretto ai Benefici (sempre visibile) --}}
                <div class="border-neutral-focus/20 my-2 border-t"></div>
                <div class="space-y-1">
                    <a href="{{ route('founders.benefits.index') }}"
                        class="{{ request()->routeIs('founders.benefits.*') ? 'bg-primary text-primary-content shadow-sm' : 'hover:bg-base-content hover:bg-opacity-10' }} flex w-full items-center justify-start gap-3 rounded-md px-3 py-2.5 text-sm transition-colors duration-150 ease-in-out">
                        <span class="flex-shrink-0">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                </path>
                            </svg>
                        </span>
                        <span class="flex-grow truncate">👑 Gestione Benefici</span>
                    </a>
                </div>
            @else
                <div class="py-12 text-center">
                    <p class="text-neutral-content opacity-60">Nessun menu disponibile</p>
                    <p class="mt-2 text-xs text-neutral-content opacity-40">Context: {{ $context }}</p>
                </div>
            @endif
        </div>
    </aside>
</div>
