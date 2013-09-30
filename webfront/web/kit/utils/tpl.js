define(function(require) {
    //requirements
    var amdLoader = require('kit/libs/require/amd-loader'),
        templateCompiler = require('templateCompiler');

    return amdLoader('tpl', function(name, source, req, callback, errback, config) {

        var content = 'define(function(require){return ' + templateCompiler(source) + '})';

        callback(content);
    });
});