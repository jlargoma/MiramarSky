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
      "css/animate.css",
      '/frontend/animate.css',
      '/frontend/magnific-popup.css',
      '/frontend/colors.css',
      '/frontend/rs-plugin/settings.css',
      '/frontend/rs-plugin/layers.css',
      '/frontend/rs-plugin/navigation.css',
      '/frontend/font-icons.css',
      '/frontend/aos.css',
      '/frontend/daterangepicker.css',
      '/frontend/radio-checkbox.css',
      '/frontend/styles.css',
      '/frontend/camera.css',
      '/frontend/lightslider.css',
    ], 'public/css/frontend.css');
});

//elixir(function (mix) {
//    mix.styles([
//                "include/rs-plugin/settings.css",  
//                "include/rs-plugin/layers.css",
//                "include/rs-plugin/navigation.css"
//    ], 'public/css/slider.css');
//});
//
//elixir(function(mix) {
//    mix.combine([
//		'resources/assets/css/js/jquery.js',
//        'resources/assets/css/js/plugins.js',
//        'resources/assets/css/js/functions.js',
//
//    ],  'public/js/scripts.js');
//});
//elixir(function(mix) {
//    mix.combine([
//        'resources/assets/css/include/rs-plugin/js/jquery.themepunch.tools.min.js',
//        'resources/assets/css/include/rs-plugin/js/jquery.themepunch.revolution.min.js',
//        'resources/assets/css/include/rs-plugin/js/addons/revolution.addon.slicey.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.actions.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.carousel.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.kenburn.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.layeranimation.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.migration.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.navigation.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.parallax.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.slideanims.min.js',
//        'resources/assets/css/include/rs-plugin/js/extensions/revolution.extension.video.min.js',
//    ],  'public/js/scripts-slider.js');
//});
        