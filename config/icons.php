<?php

return [
    'styles' => [
        'default' => 'elegant',
        'elegant' => [

            // CERTIFICATE
            [
                'name'          => 'certificate_creation',
                'type'          => 'heroicon',
                'class'         => 'w-5 h-5',
                'host'          => '',
                'name_on_host'  => '',
                'html'          => '<svg fill="#34D399" viewBox="0 0 24 24" class="%class%">
                                        <path d="M19.5 8.25v-2A2.25 2.25 0 0017.25 4h-10.5A2.25 2.25 0 004.5 6.25v11.5A2.25 2.25 0 006.75 20h10.5a2.25 2.25 0 002.25-2.25v-2h-1.5v2a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V6.25a.75.75 0 01.75-.75h10.5a.75.75 0 01.75.75v2h1.5z"/>
                                        <path stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v3m1.5-1.5h-3"/>
                                    </svg>',
            ],
            [
                'name'          => 'spedizione',
                'type'          => 'heroicon',
                'class'         => 'w-5 h-5',
                'host'          => '',
                'name_on_host'  => '',
                'html'          => '<svg fill="#34D399" viewBox="0 0 24 24" class="%class%">
                                        <path d="M3 8.25A2.25 2.25 0 015.25 6h9.386a2.25 2.25 0 011.591.659l3.914 3.914A2.25 2.25 0 0121 12.386V16.5a.75.75 0 01-.75.75h-1.5a2.25 2.25 0 00-4.5 0h-6a2.25 2.25 0 00-4.5 0H3.75a.75.75 0 01-.75-.75v-7.5z"/>
                                        <path d="M7.5 18.75a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm9 0a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"/>
                                    </svg>',
            ],
            [
                'name' => 'certificate',
                'type' => 'heroicon',
                'class' => 'w-5 h-5',
                'host' => '',
                'name_on_host' => '',
                'html' => '<svg fill="#34D399" viewBox="0 0 24 24" class="%class%">
                    <path d="M12 1.5a1.5 1.5 0 00-1.5 1.5v.75H6.75A2.25 2.25 0 004.5 6v12a2.25 2.25 0 002.25 2.25h10.5A2.25 2.25 0 0019.5 18V6a2.25 2.25 0 00-2.25-2.25H13.5V3A1.5 1.5 0 0012 1.5zM12 6a3 3 0 110 6 3 3 0 010-6zm0 7.5a6 6 0 00-6 6v.75h12V19.5a6 6 0 00-6-6z" />
                </svg>'
            ],

            [
                'name' => 'new_collection',
                'type' => 'solid',
                'class' => 'w-5 h-5',
                'host' => '',
                'name_on_host' => '',
                'html' => '<svg xmlns="http://www.w3.org/2000/svg" class="%class%" viewBox="0 0 20 20">
                    <path fill="#60A5FA" d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                    <path d="M10 9v4m-2-2h4" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>',
                // Nota: il "+" rimane bianco per contrasto sul blu, ma ora è stroke="#FFFFFF"
            ],

            [
                'name' => 'wallet',
                'type' => 'heroicon',
                'class' => 'w-5 h-5',
                'host' => '',
                'name_on_host' => '',
                'html' => '<svg fill="#FBBF24" viewBox="0 0 24 24" class="%class%">
                    <path d="M3 6.75A2.25 2.25 0 015.25 4.5h13.5a2.25 2.25 0 012.25 2.25v1.5H3v-1.5zM3 9h18v8.25a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 17.25V9zm13.5 2.25a.75.75 0 100 1.5h.008a.75.75 0 000-1.5H16.5z" />
                </svg>'
            ],

            [
                'name' => 'truck',
                'type' => 'heroicon',
                'class' => 'w-5 h-5',
                'host' => '',
                'name_on_host' => '',
                'html' => '<svg fill="#60A5FA" viewBox="0 0 24 24" class="%class%">
                    <path d="M3 4.5A1.5 1.5 0 014.5 3h11.25a1.5 1.5 0 011.5 1.5v9h2.086a1.5 1.5 0 011.059.44l1.914 1.914a1.5 1.5 0 01.44 1.06V18a1.5 1.5 0 01-1.5 1.5H19.5a1.5 1.5 0 01-3 0H7.5a1.5 1.5 0 01-3 0H3V4.5z" />
                </svg>'
            ],

            [
                'name' => 'folder-collection',
                'type' => 'heroicon',
                'class' => 'w-5 h-5',
                'host' => '',
                'name_on_host' => '',
                'html' => '<svg fill="#A78BFA" viewBox="0 0 24 24" class="%class%">
                    <path d="M2.25 5.25A2.25 2.25 0 014.5 3h4.379a2.25 2.25 0 011.591.659l.621.621H19.5A2.25 2.25 0 0121.75 6.75v1.5H2.25v-3zM2.25 9h19.5v9.75a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V9z" />
                </svg>'
            ],

            [
                'name' => 'chart-bar',
                'type' => 'heroicon',
                'class' => 'w-5 h-5',
                'host' => '',
                'name_on_host' => '',
                'html' => '<svg fill="#F87171" viewBox="0 0 24 24" class="%class%">
                    <path d="M3 3.75A.75.75 0 013.75 3h.5a.75.75 0 01.75.75V21a.75.75 0 01-.75.75h-.5A.75.75 0 013 21V3.75zM7.5 10.5a.75.75 0 01.75-.75h2a.75.75 0 01.75.75v10.5a.75.75 0 01-.75.75h-2a.75.75 0 01-.75-.75V10.5zm6-6a.75.75 0 01.75-.75h2a.75.75 0 01.75.75V21a.75.75 0 01-.75.75h-2a.75.75 0 01-.75-.75V4.5zm6 9a.75.75 0 01.75-.75h2a.75.75 0 01.75.75v7.5a.75.75 0 01-.75.75h-2a.75.75 0 01-.75-.75v-7.5z" />
                </svg>'
            ],
        ],

    ],

];
