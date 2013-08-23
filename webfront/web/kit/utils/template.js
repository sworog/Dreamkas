define(function(require) {
    //requirements
    var app = require('kit/app'),
        $ = require('jquery'),
        _ = require('underscore');

    var reText = /<%text([\s\S]+?)%>/g,
        reAttr = /<%attr([\s\S]+?)%>/g;

    _.extend(_.templateSettings.imports, {
        app: {
            isAllow: require('kit/utils/isAllow'),
            text: require('kit/utils/text'),
            attr: require('kit/utils/attr'),
            templates: app.templates
        }
    });

    _.templateSettings.interpolate = /<%=([\s\S]+?)%>/g;

    return function(template, data, options) {

        template = template
            .replace(reAttr, function(match, code) {
                code = $.trim(code);

                var list = code.split(':'),
                    model = list[0],
                    attr = list[1];

                return '<%- app.attr(' + model + ', "' + attr + '") %>';
            })
            .replace(reText, function(match, code) {
                return '<%- app.text(' + code + ') %>';
            });

        return _.template(template, data, options);
    };
});