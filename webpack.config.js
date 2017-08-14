var webpack = require('webpack')

module.exports = {
  output: {
    filename: 'bundle.js'
  },
  resolve: {
    modules: ['node_modules'],
  },
  plugins: [
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      Popper: ['popper.js', 'default']
    })
  ]
}
