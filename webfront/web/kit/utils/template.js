define(function(require) {
    //requirements
    var _ = require('underscore');

    _.templateSettings.interpolate = /<%=([\s\S]+?)%>/g;

    return _.template;
});