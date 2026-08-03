import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
                body: ['Manrope', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    50: '#f0f6ef',
                    100: '#e4efe4',
                    200: '#c9dfc9',
                    300: '#6fae6e',
                    400: '#4f9359',
                    500: '#3f8a5c',
                    600: '#2a6f48',
                    700: '#1f5c3f',
                    800: '#1a4a33',
                    900: '#173d2b',
                },
                cream: '#f7f9f6',
                ink: {
                    DEFAULT: '#22281f',
                    soft: '#5b6259',
                },
                line: '#e6e9e2',
            },
        },
    },



    plugins: [forms],
};
