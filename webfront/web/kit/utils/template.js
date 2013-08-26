define(function(require) {
    //requirements
    var app = require('kit/app'),
        _ = require('underscore');

    _.templateSettings.interpolate = /<%=([\s\S]+?)%>/g;

    return _.template;
});