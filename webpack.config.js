const path = require('path');
const webpackConfig = require('@nextcloud/webpack-vue-config');

const buildMode = process.env.NODE_ENV;

const appId = 'integration_libretranslate';
webpackConfig.entry = {
	adminSettings: {
        import: path.join(__dirname, 'src', 'adminSettings.js'),
        filename: appId + '-adminSettings.js'
    },
}

module.exports = webpackConfig;