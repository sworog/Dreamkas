var specs = Object.keys(window.__karma__.files).filter(function (file) {
    return /spec\.js$/.test(file);
});

require({
    baseUrl: '/base',
    paths: {
        'underscore': 'libs/lodash/lodash',
        'templateCompiler': 'utils/template',

        'backbone': 'libs/backbone/backbone',
        'backbone.queryparams': 'libs/backbone/backbone.queryparams',

        'jquery': 'libs/jquery/jquery',
        'jquery.require': 'libs/jquery/jquery.require',

        'tpl': 'utils/tpl',
        'i18n': 'libs/require/i18n'
    },
    packages: [
        {
            name: 'kit',
            location: '/base'
        }
    ],
    deps: specs,
    callback: window.__karma__.start
});