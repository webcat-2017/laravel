const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/login.js', 'public/js')
    .js('resources/js/backend.js', 'public/js')
    .js('resources/js/list.js', 'public/js')
    .postCss('resources/css/login.css', 'public/css')
    .postCss('resources/css/backend.css', 'public/css')
    .postCss('resources/css/list.css', 'public/css')
    .postCss('resources/css/style.css', 'public/css')
    .version();
