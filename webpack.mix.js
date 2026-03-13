const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/map.js', 'public/js')
    .js('resources/js/admin/store.js', 'public/js')
    .js('resources/js/admin/product.js', 'public/js')
    .js('resources/js/admin/product_report.js', 'public/js')
    .js('resources/js/admin/prices.js', 'public/js')
    .js('resources/js/admin/chain.js', 'public/js')
    .js('resources/js/admin/brand.js', 'public/js')
    .js('resources/js/admin/category.js', 'public/js')
    .js('resources/js/admin/tag.js', 'public/js')
    .js('resources/js/admin/valuelist.js', 'public/js')
    .js('resources/js/admin/user.js', 'public/js')
    .js('resources/js/admin/review.js', 'public/js')
    .js('resources/js/admin/dashboard.js', 'public/js')
    .js('resources/js/utils.js', 'public/js');