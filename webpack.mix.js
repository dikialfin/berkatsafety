const mix = require('laravel-mix');
const path = require('path')

mix.webpackConfig({
    resolve: {
       extensions: ['.js', '.vue'],
       alias: {
          '@': path.resolve(__dirname , 'resources'),
          vue: '@vue/compat'
       }
    },
    module: {
      rules: [
        {
          test: /\.vue$/,
          loader: 'vue-loader',
          options: {
            compilerOptions: {
              compatConfig: {
                MODE: 3
              }
            }
          }
        }
      ],
    },
    output: {
      chunkFilename: mix.inProduction()? 'dist/js/[chunkhash].js' : 'dist/js/[name].js',
    },
});

mix.js('resources/js/app.js', 'public/dist/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/dist/css')
    .postCss('resources/css/tailwind.css', 'public/dist/css', [
        require("tailwindcss"),
    ]);

if (mix.inProduction()) {
   mix.version();
}
