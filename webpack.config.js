const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');

const isProduction = process.env.NODE_ENV === 'production';

module.exports = {
    entry: {
        'faq-accordion': './assets/src/js/faq-accordion.js', // JS entry point
    },
    output: {
        path: path.resolve(__dirname, 'assets/build'),
        filename: 'js/[name].min.js', // Output JS file
    },
    module: {
        rules: [
            {
                test: /\.scss$/, // SCSS processing
                use: [
                    MiniCssExtractPlugin.loader, // Extract CSS into separate files
                    'css-loader',
                    'sass-loader', // Compiles SCSS to CSS
                ],
            },
            {
                test: /\.js$/, // JS processing with Babel
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                    },
                },
            },
        ],
    },
    plugins: [
        new CleanWebpackPlugin(), // Clean output directory before each build
        new MiniCssExtractPlugin({
            filename: 'css/[name].min.css', // Output CSS file
        }),
        new ImageMinimizerPlugin({
            test: /\.(png|jpe?g|gif|webp)$/i,
            minimizer: {
                implementation: ImageMinimizerPlugin.imageminGenerate,
                options: {
                    plugins: [['imagemin-webp', { quality: 50 }]],
                },
            },
        }),
    ],
    optimization: {
        minimize: isProduction,
        minimizer: [
            new TerserPlugin(), // Minify JavaScript files
            new CssMinimizerPlugin(), // Minify CSS files
        ],
    },
    mode: isProduction ? 'production' : 'development',
    devtool: isProduction ? false : 'source-map',
};
