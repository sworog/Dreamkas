define(function(require) {
    //requirements
    var _ = require('underscore'),
        setter = require('../mixins/setter'),
        getter = require('../mixins/getter'),
        Backbone = require('backbone'),
        cookie = require('../utils/cookie'),
        req = require('../utils/require');

    return _.extend({
            permissions: {},
            templates: {},
            apiUrl: null,
            currentPage: null,
            locale: cookie.get('locale'),
            isStarted: false,

            start: function(deps) {
                var app = this;

                req({
                    locale: app.locale
                }, deps, function() {

                    Backbone.history.start({
                        pushState: true
                    });

                    app.set('isStarted', true);
                });
            },
            'set:locale': function(locale) {
                cookie.set('locale', locale);
                document.location.reload();
            }
        },
        getter,
        setter,
        Backbone.Events);
});