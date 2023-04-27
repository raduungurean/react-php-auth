const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
    // other configuration options...
    plugins: [
        new CopyWebpackPlugin({
            patterns: [
                { from: 'public/.htaccess', to: './' },
            ],
        }),
    ],
};