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

mix.copy('resources/assets/img', 'public/img');
mix.copy('resources/assets/docs', 'public/assets/docs');
mix.copy('resources/assets/fonts', 'public/fonts');
mix.copy('resources/assets/js/vendor/font-awesome/fonts', 'public/fonts');
mix.copy('resources/assets/js/vendor/bootstrap/dist/fonts', 'public/fonts');
mix.copy('resources/assets/js/vendor/jquery.rateit/scripts/star.gif', 'public/css/star.gif');
mix.copy('resources/assets/js/vendor/jquery.rateit/scripts/delete.gif', 'public/css/delete.gif');
mix.copy('resources/assets/js/vendor/blueimp-gallery/img', 'public/css/img');
mix.copy('resources/assets/img/loading-spinner.gif', 'public/img/loading-spinner.gif');
//mix.copy('resources/assets/admin/ace/img/clear.png', 'public/css/img/clear.png');
mix.copy('resources/assets/admin/ace/img/clear.png', 'public/img/clear.png');
//mix.copy('resources/assets/img/allsecure-banner-100x110.gif', 'public/img/allsecure-banner-100x110.gif');
mix.copy('resources/assets/admin/ace/css/images/spritemap.png', 'public/images/spritemap.png');
mix.copy('resources/assets/admin/ace/css/images/spritemap@2x.png', 'public/images/spritemap@2x.png');

//Allsecure
mix.copy('resources/assets/allsecure/img/', 'public/assets/allsecure/img');
mix.styles('resources/assets/allsecure/payment.css', 'public/assets/allsecure/payment.css');

mix.copy('resources/assets/js/vendor/slick-carousel/slick/fonts', 'public/css/fonts');

//GUMAMAX USER CSS
mix.styles([
    'resources/assets/js/vendor/bootstrap/dist/css/bootstrap.css',
    'resources/assets/js/vendor/font-awesome/css/font-awesome.css',
    'resources/assets/js/vendor/bootstrap3-dialog/dist/css/bootstrap-dialog.css',
    'resources/assets/js/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.css',
    'resources/assets/js/vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    'resources/assets/js/vendor/sweetalert/dist/sweetalert.css',
    'resources/assets/js/vendor/jquery.rateit/scripts/rateit.css',
    'resources/assets/js/vendor/dropzone/dist/dropzone.css',
    'resources/assets/js/vendor/blueimp-gallery/css/blueimp-gallery.css',
    'resources/assets/js/vendor/slick-carousel/slick/slick.css',
    'resources/assets/js/vendor/slick-carousel/slick/slick-theme.css',
    'resources/assets/css/iosOverlay.css',
    'resources/assets/css/gumamax.css',
    'resources/assets/css/eu-badge.css',
    'resources/assets/css/compare.css',
    'resources/assets/css/product-gallery.css'
], 'public/css/all.css');

mix.styles([
    'resources/assets/js/vendor/datatables.net-bs/css/dataTables.bootstrap.css',
    'resources/assets/js/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css',
], 'public/css/dmx-datatables.css');

// ACE3 ADMIN CSS
mix.styles([
    'resources/assets/js/vendor/bootstrap/dist/css/bootstrap.css',
    'resources/assets/js/vendor/font-awesome/css/font-awesome.css',
    // 'admin/ace/css/ace-fonts.css',
    'resources/assets/js/select2/css/select2-3.5.4.css',
    'resources/assets/js/select2/css/select2-3.5.4-bootstrap.css',
    'resources/assets/admin/ace/css/uncompressed/ace.css',
    'resources/assets/admin/ace/css/ace-skins.min.css',
    'resources/assets/admin/ace/css/bootstrap-editable.css',
    'resources/assets/admin/ace/css/dropzone.css',
    'resources/assets/js/vendor/jquery-colorbox/example1/colorbox.css',
    'resources/assets/admin/ace/css/admin-custom.css',
    'resources/assets/js/vendor/datatables.net-bs/css/dataTables.bootstrap.css',
    'resources/assets/js/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css',
    'resources/assets/js/vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    'resources/assets/js/vendor/bootstrap3-dialog/dist/css/bootstrap-dialog.css',
    'resources/assets/js/vendor/sweetalert/dist/sweetalert.css',
    'resources/assets/js/vendor/blueimp-gallery/css/blueimp-gallery.css',
    'resources/assets/css/iosOverlay.css'
], 'public/css/admin.css');

//ACE ADMIN JS
mix.scripts([
    'resources/assets/js/vendor/jquery/dist/jquery.js',
    'resources/assets/js/vendor/jquery-ui/jquery-ui.js',
    'resources/assets/js/vendor/bootstrap/dist/js/bootstrap.js',
    'resources/assets/js/vendor/datatables.net/js/jquery.dataTables.js',
    'resources/assets/js/vendor/datatables.net-bs/js/dataTables.bootstrap.js',
    'resources/assets/js/vendor/datatables.net-responsive/js/dataTables.responsive.js',
    'resources/assets/js/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js',
    'resources/assets/js/vendor/fastclick/lib/fastclick.js',
    'resources/assets/js/vendor/bootstrap3-dialog/dist/js/bootstrap-dialog.js',
    'resources/assets/js/vendor/sweetalert/dist/sweetalert-dev.js',
    'resources/assets/js/vendor/moment/min/moment.min.js',
    'resources/assets/js/vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    'resources/assets/js/vendor/bootstrap-paginator/src/bootstrap-paginator.js',
    'resources/assets/js/vendor/handlebars/handlebars.js',
    'resources/assets/js/vendor/vue/dist/vue.js',
    'resources/assets/js/vendor/vue-resource/dist/vue-resource.js',
    'resources/assets/js/vendor/jquery-validation/dist/jquery.validate.js',
    'resources/assets/js/vendor/spin.js/spin.js',
    'resources/assets/admin/ace/js/uncompressed/x-editable/bootstrap-editable.js',
    'resources/assets/js/vendor/jquery-colorbox/jquery.colorbox.js',
    'resources/assets/admin/ace/js/uncompressed/dropzone.js',
    'resources/assets/admin/ace/js/ace-elements.min.js',
    'resources/assets/admin/ace/js/uncompressed/ace.js',
    'resources/assets/js/vendor/blueimp-gallery/js/blueimp-gallery.js',
    'resources/assets/js/select2/js/select2-3.5.4.js',
    'resources/assets/admin/ace/js/jquery.hotkeys.min.js',
    'resources/assets/admin/ace/js/bootstrap-wysiwyg.min.js',
    'resources/assets/js/hb-common.js',
    'resources/assets/js/iosOverlay.js',
    'resources/assets/js/loading.js',
    'resources/assets/js/common.js',
    'resources/assets/js/jquery-common.js',
    'resources/assets/js/crm.js'
], 'public/js/ace-admin.js');

mix.copy('resources/assets/admin', 'public/assets/admin');

//GMX USER VENDOR js
mix.scripts([
    'resources/assets/js/vendor/devbridge-autocomplete/dist/jquery.autocomplete.js',
    'resources/assets/js/vendor/bootstrap3-dialog/dist/js/bootstrap-dialog.js',
    'resources/assets/js/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
    'resources/assets/js/vendor/sweetalert/dist/sweetalert-dev.js',
    'resources/assets/js/vendor/moment/min/moment.min.js',
    'resources/assets/js/vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    'resources/assets/js/vendor/dropzone/dist/dropzone.js',
    'resources/assets/js/vendor/bootstrap-paginator/src/bootstrap-paginator.js',
    'resources/assets/js/vendor/handlebars/handlebars.js',
    'resources/assets/js/vendor/vue/dist/vue.js',
    'resources/assets/js/vendor/vue-resource/dist/vue-resource.js',
    'resources/assets/js/vendor/jquery-validation/dist/jquery.validate.js',
    'resources/assets/js/vendor/spin.js/spin.js',
    'resources/assets/js/vendor/jquery.rateit/scripts/jquery.rateit.js',
    'resources/assets/js/vendor/jquery.cookie/jquery.cookie.js',
    'resources/assets/js/vendor/blueimp-gallery/js/blueimp-gallery.js',
    'resources/assets/js/vendor/slick-carousel/slick/slick.min.js',
], 'public/js/vendor.js');

mix.scripts([
    'resources/assets/js/vendor/datatables.net/js/jquery.dataTables.js',
    'resources/assets/js/vendor/datatables.net-bs/js/dataTables.bootstrap.js',
    'resources/assets/js/vendor/datatables.net-responsive/js/dataTables.responsive.js',
    'resources/assets/js/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js',
], 'public/js/dmx-datatables.js');

//GUMAMAX USER JS
mix.scripts([
    'resources/assets/js/iosOverlay.js',
    'resources/assets/js/common.js',
    'resources/assets/js/jquery-common.js',
    'resources/assets/js/loading.js',
    'resources/assets/js/hb-common.js',
    'resources/assets/js/gmx-hb-helpers.js',
    'resources/assets/js/gumamax.js',
    'resources/assets/js/cities.js',
    'resources/assets/js/compare.js',
    'resources/assets/js/products-load-more.js',
    'resources/assets/js/product-details.js'
], 'public/js/all.js');

mix.copy('resources/assets/js/vendor/jquery/dist/jquery.js',            'public/js/vendor/jquery.js');
mix.copy('resources/assets/js/vendor/bootstrap/dist/js/bootstrap.js',   'public/js/vendor/bootstrap.js');
mix.copy('resources/assets/js/vendor/respond/src/respond.js',           'public/js/vendor/respond.js');
mix.copy('resources/assets/js/vendor/html5shiv/dist/html5shiv.js',      'public/js/vendor/html5shiv.js');
mix.copy('resources/assets/js/vendor/dropzone/dist/dropzone.js',        'public/js/vendor/dropzone.js');

mix.copy('resources/assets/js/profile-basic-info.js',   'public/js/profile-basic-info.js');
mix.copy('resources/assets/js/profile-address.js',      'public/js/profile-address.js');
mix.copy('resources/assets/js/profile-vehicle.js',      'public/js/profile-vehicle.js');
mix.copy('resources/assets/js/register.js',             'public/js/register.js');
mix.copy('resources/assets/js/login.js',                'public/js/login.js');
mix.copy('resources/assets/js/reset-password.js',       'public/js/reset-password.js');
mix.copy('resources/assets/js/profile-pass.js',         'public/js/profile-pass.js');
mix.copy('resources/assets/js/profile-orders.js',       'public/js/profile-orders.js');
mix.copy('resources/assets/js/partners.js',             'public/js/partners.js');
mix.copy('resources/assets/js/maps.js',                 'public/js/maps.js');
mix.copy('resources/assets/js/tyre-search.js',          'public/js/tyre-search.js');
mix.copy('resources/assets/js/rating.js',               'public/js/rating.js');
mix.copy('resources/assets/js/better-price.js',         'public/js/better-price.js');
mix.copy('resources/assets/js/shop.js',                 'public/js/shop.js');
mix.copy('resources/assets/js/payment.js',              'public/js/payment.js');
mix.copy('resources/assets/js/jquery.addVehicle.js',    'public/js/jquery.addVehicle.js');
mix.copy('resources/assets/js/admin/merchant-health.js','public/js/admin/merchant-health.js');
mix.copy('resources/assets/js/working-hour-editable.js','public/js/admin/working-hour-editable.js');

mix.version();
