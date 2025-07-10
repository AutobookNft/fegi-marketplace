import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import daisyui from 'daisyui'; // Import corretto di daisyui

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    daisyui: {
        styled: true, // Applica stili di default ai componenti DaisyUI
        themes: [{
            florenceegi: { // Il nostro tema custom per DaisyUI
                "primary": "#D4A574",         // Oro Fiorentino (florence-gold.DEFAULT)
                "primary-content": "#1F2937", // Testo scuro su primario (florence-gold.text)

                "secondary": "#1B365D",       // Blu Algoritmo (blu-algoritmo.DEFAULT)
                "secondary-content": "#F9FAFB",// Testo chiaro su secondario (blu-algoritmo.text)

                "accent": "#2D5016",          // Verde Rinascita (verde-rinascita.DEFAULT)
                "accent-content": "#F9FAFB",  // Testo chiaro su accento (verde-rinascita.text)

                "neutral": "#374151",         // Grigio Pietra Scuro (grigio-pietra.dark / Tailwind gray-700)
                "neutral-content": "#D1D5DB", // Testo chiaro su neutro (grigio-pietra.light)

                "base-100": "#0E1C30",        // SFONDO PAGINA PRINCIPALE: Blu Algoritmo Molto Scuro (blu-algoritmo.dark)
                                              // Alternativa: "#111827" (Tailwind gray-900)
                "base-200": "#1F2937",        // Sfondo per card leggermente più chiaro (grigio-pietra.extradark / Tailwind gray-800)
                "base-300": "#374151",        // Sfondo per elementi ancora più chiari o bordi (grigio-pietra.dark / Tailwind gray-700)
                "base-content": "#D1D5DB",    // Colore testo di default su base-100 (grigio-pietra.light / Tailwind gray-300)

                "info": "#22D3EE",            // info.DEFAULT
                "info-content": "#064e3b",    // info.content

                "success": "#4ADE80",         // success.DEFAULT
                "success-content": "#052e16", // success.content

                "warning": "#FBBF24",         // warning.DEFAULT
                "warning-content": "#78350f", // warning.content

                "error": "#F87171",           // error.DEFAULT
                "error-content": "#7f1d1d",   // error.content

                // Aggiustamenti specifici DaisyUI
                "--rounded-box": "0.5rem", // Esempio: default 0.5rem per card, etc. (era 1rem)
                "--rounded-btn": "0.375rem", // Esempio: default 0.375rem per bottoni (era 0.5rem)
                // "--btn-text-case": "none", // Se non vuoi i bottoni in uppercase di default
            },
        }],
        base: true,        // Applica stili di base (come normalize)
        utils: true,       // Applica classi utility di DaisyUI
        logs: false,       // Riduce i log in console
        rtl: false,
        prefix: "",        // Nessun prefisso per le classi DaisyUI (es. usa `btn` non `du-btn`)
    },

    plugins: [
        forms,
        typography,
        daisyui // Usa la variabile importata
    ],
};
