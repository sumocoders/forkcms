/** @type {import('postcss-load-config').Config} */
const config = {
  // object form lets postcss-loader load each plugin itself, which handles ESM-only plugins like postcss-nested
  plugins: {
    autoprefixer: {},
    'postcss-nested': {},
    'postcss-easing-gradients': {}
  }
}

module.exports = config
