var elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir.config.sourcemaps = !process.argv.slice(2).find(elem => elem === '--production');


elixir(mix => {
    mix.sass('app.scss')
        // .styles(['../../../node_modules/html5-marquee/css/marquee.css'], 'public/css/vendor.css')
        .webpack('app.js')
        .version(['js/app.js', 'css/app.css']);
});
