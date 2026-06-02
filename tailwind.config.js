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
                    50: '#f0f7ff',
                    100: '#e0effe',
                    200: '#bbddfe',
                    300: '#7cc0fd',
                    400: '#369ffa',
                    500: '#0c82eb',
                    600: '#0265c9',
                    700: '#0250a3',
                    800: '#064585',
                    900: '#0b3b6f',
                    950: '#07254a',
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
