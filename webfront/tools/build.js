({
    baseUrl: '../src/',
    mainConfigFile: '../src/require.config.js',

    stubModules: ['ejs', 'amd-loader'],
    skipDirOptimize: true,
    optimizeCss: 'none',
    optimize: 'none',
    optimizeAllPluginResources: true,
    removeCombined: true,

    dir: "../build",
    
    modules: [
        {
            name: "routes/unauthorized",
            exclude: ['app', 'jquery']
        },
        {
            name: "routes/authorized",
            exclude: ['app', 'jquery']
        },
        {
            name: "main",
            exclude: ['jquery'],
            include: ['app']
        }
    ]

})