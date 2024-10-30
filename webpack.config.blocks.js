const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
  ...defaultConfig,
  mode: 'production',
  entry: {
    'blocks': path.resolve(__dirname, 'assets/js/src/blocks/index.js'),
  },
  output: {
    ...defaultConfig.output,
    path: path.resolve(__dirname, 'assets/js/build/blocks')
  }
};
