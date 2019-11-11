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
      '/frontend/camera.css',
      '/frontend/lightslider.css',
      '/frontend/styles.css',
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
//

  
elixir(function(mix) {
    mix.combine([
    'resources/assets/js/camera/modernizr-3.5.0.min.js',
    'resources/assets/js/camera/easing.min.js',
    'resources/assets/js/camera/camera.min.js',
    'resources/assets/js/camera/bootstrap.min.js',
    'resources/assets/js/camera/plugins.js',
    'resources/assets/js/lightslider/lightslider.js',
    'resources/assets/js/form_booking.js',
    ],  'public/js/scripts-home.js');
});


  
elixir(function(mix) {
    mix.combine([
      'resources/assets/js/footer/plugins.js',
      'resources/assets/js/footer/functions.js',
      'resources/assets/js/footer/progressbar.min.js',
      'resources/assets/js/footer/scripts.js',
      'resources/assets/js/footer/flip.min.js',
      'resources/assets/js/footer/plugins.js',
      'resources/assets/js/footer/moment.min.js',
      'resources/assets/js/footer/daterangepicker.min.js',
      'resources/assets/js/footer/aos.js',
      'resources/assets/js/footer/jquery.flip.min.js'
    ],  'public/js/scripts-footer.js');
});
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
        