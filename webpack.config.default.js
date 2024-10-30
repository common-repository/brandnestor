const path = require('path');

module.exports = {
  mode: 'production',
  module: {
    rules: [
      {
        test: /\.svg$/i,
        type: 'asset/source',
      },
    ]
  },
  entry: {
    'login': path.resolve(__dirname, 'assets/js/src/login.js'),
    'settings': path.resolve(__dirname, 'assets/js/src/settings.js'),
  }
};
