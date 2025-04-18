import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['GeistVF', 'GeistMonoVF', ...defaultTheme.fontFamily.sans],
                geist: ['GeistVF', 'sans-serif'],
                geistmono: ['GeistMonoVF', 'sans-serif'],
                nanum: ['Nanum Myeongjo', 'serif'],
                slabo: ['Slabo 27px', 'serif'],


            },
        },
    },
    plugins: [
        require("daisyui"),
        require('flowbite/plugin')
    ]
};