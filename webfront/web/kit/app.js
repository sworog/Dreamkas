define(function(require) {
    //requirements
    var _ = require('underscore'),
        setters = require('kit/utils/setters'),
        getters = require('kit/utils/getters'),
        Backbone = require('backbone'),
        cookie = require('kit/utils/cookie'),
        req = require('kit/utils/require');

    var app = _.extend({
            permissions: {},
            templates: {},
            apiUrl: null,
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
        getters,
        setters,
        Backbone.Events);

    window.LH = window.Lighthouse = _.extend({
        templates: app.templates
    }, window.LH, window.Lighthouse);

    return app;
});