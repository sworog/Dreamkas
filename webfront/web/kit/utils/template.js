define(function(require) {
    //requirements
    var _ = require('lodash');

    _.templateSettings.interpolate = /<%=([\s\S]+?)%>/g;

    return _.template;
});