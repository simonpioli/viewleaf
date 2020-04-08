'use strict';

const mix = require('laravel-mix');

mix.sass('resources/assets/sass/app.scss', 'public/css')
    // .styles(['../../../node_modules/html5-marquee/css/marquee.css'], 'public/css/vendor.css')
    .js('resources/assets/js/app.js', 'public/js');

if (mix.inProduction()) {
    mix.version();
}

