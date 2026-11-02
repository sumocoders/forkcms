const { merge } = require('webpack-merge')
const common = require('./webpack.common.js')
const backend = require('./webpack.backend.js')

module.exports = [
  merge(common, {
    mode: 'development',
    devtool: 'inline-source-map'
  }),
  merge(backend, {
    mode: 'development'
  })
]
