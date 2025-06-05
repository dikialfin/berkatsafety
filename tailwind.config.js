const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    prefix: 'tw-',
    corePlugins: {
        preflight: false,
    },
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        // 'node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx}',
        // 'node_modules/flowbite/**/*.{js,jsx,ts,tsx}'
    ],

    theme: {
        extend: {
            fontFamily: {
                //
            },
        },
    },

    plugins: [
        // require('flowbite/plugin')
    ],
};
