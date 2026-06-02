import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbite from 'flowbite/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                bps: {
                    50: '#fff7ed',   // orange-50
                    100: '#ffedd5',  // orange-100
                    200: '#fed7aa',  // orange-200 (light)
                    300: '#fdba74',  // orange-300
                    400: '#fb923c',  // orange-400 (soft)
                    500: '#f97316',  // orange-500 (primary)
                    600: '#ea580c',  // orange-600 (dark)
                    700: '#c2410c',  // orange-700
                    800: '#9a3412',  // orange-800
                    900: '#7c2d12',  // orange-900
                    950: '#431407',  // orange-950
                },
                progress: {
                    red: '#ef4444',
                    yellow: '#f59e0b',
                    green: '#10b981',
                }
            }
        },
    },

    plugins: [forms, flowbite],
};
