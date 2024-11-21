import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'purple-start': '#7f00ff',
                'purple-transparent-start': '#7f00ff11',
                'purple-end': '#e100ff',
                'purple-transparent-end': '#e100ff11',
                'yellow-start': '#F7971E',
                'yellow-end': '#FFD200',
            },
        },
    },
    plugins: [],
};
