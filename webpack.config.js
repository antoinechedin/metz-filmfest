var Encore = require('@symfony/webpack-encore');

Encore
// the project directory where compiled assets will be stored
    .setOutputPath('www/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/login', './assets/css/login.scss')
    .addStyleEntry('css/oi', './assets/css/open-iconic-bootstrap.scss')
    .addStyleEntry('css/admin', './assets/css/admin.scss')

    .enableSassLoader(function (sassOptions) {
    }, {
        resolveUrlLoader: false
    })

    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
