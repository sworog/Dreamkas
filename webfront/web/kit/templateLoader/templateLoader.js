define(function(require) {
    //requirements
    var amdLoader = require('bower_components/amd-loader/amd-loader'),
        templateCompiler = require('templateCompiler');

    return amdLoader('tpl', function(name, source, req, callback, errback, config) {

        var content = 'define(function(require){return ' + templateCompiler(source) + '})';

        callback(content);
    });
});