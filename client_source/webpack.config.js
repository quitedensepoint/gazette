const path = require('path');

const ExtractTextPlugin = require('extract-text-webpack-plugin');

const extractSass = new ExtractTextPlugin({
    filename: 'assets/css/styles.css',
    disable: process.env.NODE_ENV === 'development'
});

module.exports = {
    entry: path.resolve(__dirname, './src/app.js'),
    output: {
        path: path.resolve(__dirname, '../web'),
        filename: 'bundle.js',
        publicPath: '../../'
    },
    devtool: 'source-map',
    module: {
        rules: [{
                test: /\.js$/,
                exclude: [/node_modules/],
                use: [{
                    loader: 'babel-loader',
                    options: {
                        presets: ['es2016']
                    }
                }],
            },
            {
                test: /\.scss$/,
                loader: extractSass.extract({
                    loader: [{
                            loader: 'css-loader'
                        },
                        {
                            loader: 'sass-loader'
                        }
                    ]
                })
            },
            {
                test: /\.woff$|\.eot$|\.woff2$|\.ttf$|\.svg$/,
                use: [{
                    loader: 'url-loader',
                    options: {
                        limit: 50000,
                        mimetype: 'application/font-[ext]',
                        name: './fonts/[name].[ext]',
                    },
                }]
            },
            {
                test: /\.png$/,
                use: [{
                    loader: 'file-loader',
                    options: {
                        name: 'assets/img/[name].[ext]'
                    }
                }]
            }
        ]
    },
    plugins: [
        extractSass
    ]
};
