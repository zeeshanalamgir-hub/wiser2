const mix = require('laravel-mix');
const webpack = require('webpack'); // Import webpack

const env = process.env.NODE_ENV === 'production' ? 'production' : 'local';
const environmentConfig = require(`./resources/js/env.${env}.js`);

mix.webpackConfig({
    plugins: [
        new webpack.DefinePlugin({
            'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development'), // Replace NODE_ENV
            'process.env.REVERB_APP_KEY': JSON.stringify(environmentConfig.REVERB_APP_KEY),
            'process.env.REVERB_WS_HOST': JSON.stringify(environmentConfig.REVERB_HOST),
            'process.env.REVERB_WS_PORT': JSON.stringify(environmentConfig.REVERB_PORT),
            'process.env.REVERB_FORCE_TLS': JSON.stringify(environmentConfig.REVERB_FORCE_TLS),
            'process.env.REVERB_TRANSPORTS': JSON.stringify(environmentConfig.REVERB_TRANSPORTS),
            'process.env.ENCRYPTED': JSON.stringify(environmentConfig.ENCRYPTED)
        })
    ]
});

mix.js('resources/js/app.js', 'public/js')
    .babelConfig({
        presets: ['@babel/preset-env']
    })
    .version();

mix.js('resources/js/zoom-meeting.js', 'public/js');
