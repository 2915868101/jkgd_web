const path = require('path')
const autoprefixer = require('autoprefixer')
const pxtorem = require('postcss-pxtorem')

function resolve(dir) {
  return path.join(__dirname, dir)
}
module.exports = {
  outputDir: 'dist',
  assetsDir: "assets",
  publicPath: process.env.NODE_ENV === 'production' ? './' : '/',
  devServer: {
    open: true,
    host: '0.0.0.0',
    port: 3200
  },
  pages: {
    //配置入口文件
    index: {
      entry: 'src/pages/index/main.js',
      template: 'src/pages/index/index.html',
      chunks: ['chunk-vendors', 'chunk-common', 'index']
    }
  },
  transpileDependencies: [
    'vue-echarts',
    'resize-detector'
  ],
  productionSourceMap: false,
  css: {
    extract: true,
    sourceMap: false,
    loaderOptions: {
      //rem适配
      postcss: {
        plugins: [
          autoprefixer(),
          pxtorem({
            rootValue: 37.5,
            propList: ['*']
          })
        ]
      }
    }
  },
  // configureWebpack: (config) => {
  //   require('vux-loader').merge(config, {
  //     plugins: ['vux-ui']
  //   })
  // },
  chainWebpack: (config) => {
    // 移除 prefetch 插件(针对生产环境首屏请求数进行优化)
    config.plugins.delete('prefetch')
    // 移除 preload 插件(针对生产环境首屏请求数进行优化)
    config.plugins.delete('preload')
    // 设置路径别名
    config.resolve.alias
      .set('@', resolve('./src'))
      .set('^', resolve('./public/static'))
    // 图片压缩
    // config.module
    //   .rule('images')
    //   .use('image-webpack-loader')
    //   .loader('image-webpack-loader')
    //   .options({
    //     bypassOnDebug: true
    //   })
    //   .end()
  }
}