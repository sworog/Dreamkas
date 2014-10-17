define(function(require) {
    //requirements
    var _ = require('bower_components/lodash/dist/lodash');

    _.templateSettings.interpolate = /<%=([\s\S]+?)%>/g;

    return _.template;
});