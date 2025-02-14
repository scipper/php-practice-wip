const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const isProduction = process.env.NODE_ENV === "production";

const stylesHandler = isProduction ? MiniCssExtractPlugin.loader : "style-loader";


const config = {
    entry: "./src/main/ts/Main.ts",
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: '[name].bundle.js',
    },
    devtool: "source-map",
    devServer: {
        open: true,
        host: "localhost",
        hot: true,
        watchFiles: ["./src/main/resources/*"],
        proxy: [
            {
                context: ['/api'],
                target: 'http://localhost:8080',
            },
        ],
    },
    plugins: [
        new HtmlWebpackPlugin({
            template: "./src/main/resources/index.html"
        })
    ],
    module: {
        rules: [
            {
                test: /\.(ts|tsx)$/i,
                loader: "ts-loader",
                exclude: ["/node_modules/"]
            },
            {
                test: /\.s[ac]ss$/i,
                use: [stylesHandler, "css-loader", "sass-loader"]
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2|png|jpg|gif)$/i,
                type: "asset"
            }
        ]
    },
    resolve: {
        extensions: [".tsx", ".ts", ".jsx", ".js", "..."]
    }
};

module.exports = () => {
    if (isProduction) {
        config.mode = "production";

        config.plugins.push(new MiniCssExtractPlugin());


    } else {
        config.mode = "development";
    }
    return config;
};
