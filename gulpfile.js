var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

// elixir(function(mix) {
//     mix.sass('app.scss');
// });
elixir(function (mix) {
    mix.styles([
                "css/bootstrap.css",
                "style.css",
                "css/font-icons.css",
                "css/animate.css",
                "css/magnific-popup.css",
				"css/responsive.css"
    ], 'public/css/app.css');
});

elixir(function (mix) {
    mix.styles([
                "include/rs-plugin/settings.css",  
                "include/rs-plugin/layers.css",
                "include/rs-plugin/navigation.css"
    ], 'public/css/slider.css');
});

elixir(function(mix) {
    mix.combine([
		'resources/assets/css/js/jquery.js',
        'resources/assets/css/js/plugins.js',
        'resources/assets/css/js/functions.js',
        'resources/assets/css/include/rs-plugin/js/jquery.themepunch.tools.min.js',
        'resources/assets/css/include/rs-plugin/js/jquery.themepunch.revolution.min.js',
        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.video.min.js',
        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js',
        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.actions.min.js',
        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js',
        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js',
    ],  'public/js/scripts.js');
});
