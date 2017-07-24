module.exports = {
    module: {
        loaders: [
            {include: /\.json$/, loaders: ["json-loader"]}
        ]
    },
    resolve: {
        extensions: ['', '.json', '.vue', '.js']
    }
};
