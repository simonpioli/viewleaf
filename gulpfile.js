var elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir(mix => {
    mix.sass('app.scss')
       // .styles(['../../../node_modules/html5-marquee/css/marquee.css'], 'public/css/vendor.css')
       .webpack('app.js')
       .version(['js/app.js', 'css/app.css']);
});
