const path = require('path');
const webpack = require('webpack');
const GitRevisionPlugin = require('git-revision-webpack-plugin')

module.exports = {
  entry: {
    'order/create': './src/views/orderForm/create.js',
    'order/update': './src/views/orderForm/create.js',
    'order/orderList': './src/views/orderList.js',
  },
  mode: 'development',
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /(node_modules|bower_components)/,
        loader: 'babel-loader',
        options: { presets: ['@babel/env'] }
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader']
      }
    ]
  },
  resolve: { extensions: ['*', '.js', '.jsx'] },
  output: {
    path: path.resolve(__dirname, '../web/dist/'),
    publicPath: '../web/dist/',
    filename: '[name]-[git-revision-hash].js'
  },
  devServer: {
    contentBase: path.join(__dirname, 'public/'),
    port: 3000,
    publicPath: 'http://localhost:3000/dist/',
    hotOnly: true
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin(),
    new webpack.EnvironmentPlugin({
      ENVIRONMENT: process.env.ENVIRONMENT || 'development'
    }),
    new GitRevisionPlugin()
  ]
};
