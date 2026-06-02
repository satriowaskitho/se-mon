

import './bootstrap';
import 'flowbite';
import './dashboard-charts';

// Only load and boot Alpine from Vite bundle if Livewire v3 is NOT loaded.
// Livewire v3 automatically injects and boots Alpine globally when present.
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Alpine) {
        import('alpinejs').then(({ default: Alpine }) => {
            window.Alpine = Alpine;
            Alpine.start();
        });
    }
});


