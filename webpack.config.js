const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');
const dotenv = require('dotenv');
const fs = require('fs');

const envFile = fs.existsSync('.env.local') ? '.env.local' : '.env';

dotenv.config({ path: envFile });

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .enableVueLoader()
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .enablePostCssLoader()
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .addPlugin(new webpack.DefinePlugin({
        'process.env.API_KEY': JSON.stringify(process.env.API_KEY)
    }));

Encore.addRule({
    test: /\.html$/,
    use: ['html-loader'],
});

module.exports = Encore.getWebpackConfig();
