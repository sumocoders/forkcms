/** @type {import('postcss-load-config').Config} */
const config = {
  plugins: [
    require('autoprefixer'),
    require('postcss-nested'),
    require('postcss-easing-gradients')
  ]
}

module.exports = config
