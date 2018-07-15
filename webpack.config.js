'use strict'

const path = require('path')
const webpack = require('webpack')
const MiniCssExtractPlugin = require("mini-css-extract-plugin")

let plugins = [
	new MiniCssExtractPlugin({
		// Options similar to the same options in webpackOptions.output
		// both options are optional
		filename: "styles.css",
		chunkFilename: "[id].css"
	})
]

// Get the environment variable defined in the command (see package.json)
const env = process.env.WEBPACK_ENV

const __DEV__ = env === 'development';
const __PROD__ = env === 'production';

// When compiling for production
// if (env === 'production') {
// 	plugins.push(new UglifyPlugin({ minimize: true }))
// }

let postcssPlugins = [
	require('precss')(),
	require('autoprefixer')(),
	require('cssnano')()
];

if ( __DEV__ ) {
	postcssPlugins = [
		require('precss')(),
		require('autoprefixer')()
	];
}

// Main webpack config
module.exports = {
	mode: env,
	entry: {
		app: [ 'babel-polyfill', './web/assets/js/site.js', './web/assets/css/site.scss' ]
	},
	output: {
		path: path.resolve(__dirname, 'web/assets'),
		filename: 'scripts.js'
	},
	module: {
		rules: [
			{
				test: /\.jsx?$/,
				exclude: /(node_modules|bower_components)/,
				include: [path.resolve(__dirname, './node_modules/vue2-google-maps')],
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['env']
					}
				}
			},
			{
				test: /\.s?[ac]ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					{
						loader: 'postcss-loader',
						options: {
							plugins: (loader) => postcssPlugins
						}
					},
					'sass-loader',
				],
			},
			// NOTE not needed since no .vue files
			// {
			// 	test: /\.vue$/,
			// 	loader: 'vue-loader'
			// }
		]
	},
	resolve: {
		alias: {
			'vue$': 'vue/dist/vue.esm.js' // Resolving the vue var for standalone build
		}
	},
	plugins, // set the previously defined plugins
}
