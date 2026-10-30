const { merge } = require('webpack-merge')
const common = require('./webpack.common.js')
const backend = require('./webpack.backend.js')

module.exports = [
  merge(common, {
    mode: 'production'
  }),
  merge(backend, {
    mode: 'production'
  })
]
