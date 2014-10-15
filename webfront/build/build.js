({
    baseUrl: '../dev/',
    mainConfigFile: '../dev/require.config.js',

    stubModules: ['ejs'],
    skipDirOptimize: true,
    optimizeCss: 'none',
    optimize: 'none',
    optimizeAllPluginResources: true,
    removeCombined: true,

    dir: "../production",
    
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