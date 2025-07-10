<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlorenceEGI - Certificati Padri Fondatori</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

    {{-- Custom CSS for FlorenceEGI Brand --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Source+Sans+Pro:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Source Sans Pro', sans-serif;
    }

    /* FlorenceEGI Brand Colors */
    :root {
        --color-oro-fiorentino: #D4A574;
        --color-verde-rinascita: #2D5016;
        --color-blu-algoritmo: #1B365D;
        --color-grigio-pietra: #6B6B6B;
    }

    /* Golden Ratio Proportions */
    .ratio-golden {
        aspect-ratio: 1.618 / 1;
    }

    /* Rinascimento Form Styling */
    .form-rinascimento {
        background: linear-gradient(135deg, #ffffff 0%, #fef3e2 100%);
        box-shadow: 0 25px 50px -12px rgba(212, 165, 116, 0.15);
    }

    /* Animation for certificate emission */
    @keyframes certificateGlow {
        0% { box-shadow: 0 0 5px rgba(212, 165, 116, 0.3); }
        50% { box-shadow: 0 0 20px rgba(212, 165, 116, 0.6); }
        100% { box-shadow: 0 0 5px rgba(212, 165, 116, 0.3); }
    }

    .certificate-glow {
        animation: certificateGlow 2s ease-in-out infinite;
    }
</style>

{{-- Alpine.js for additional interactivity --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        // Listen for certificate issued event
        Livewire.on('certificate-issued', (event) => {
            // Add celebration animation
            const form = document.querySelector('.bg-white.rounded-2xl');
            if (form) {
                form.classList.add('certificate-glow');
                setTimeout(() => {
                    form.classList.remove('certificate-glow');
                }, 4000);
            }

            // Optional: Show browser notification
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('🎉 Certificato Padre Fondatore Emesso!', {
                    body: `Certificato #${event[0].certificate_number} creato con successo`,
                    icon: '/favicon.ico'
                });
            }
        });

        // Auto-refresh statistics every 30 seconds
        setInterval(() => {
            Livewire.dispatch('loadStatistics');
        }, 30000);
    });
</script>


    @livewire('founder-certificate-form')

    @livewireScripts


</body>
</html>
