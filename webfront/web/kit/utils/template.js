define(function(require) {
    //requirements
    require('lodash');

    _.templateSettings.interpolate = /<%=([\s\S]+?)%>/g;

    return _.template;
});