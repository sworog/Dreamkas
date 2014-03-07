define(function(require) {
    //requirements
    var setter = require('../utils/setter'),
        getter = require('../utils/getter'),
        router = require('router'),
        cookie = require('cookies');

    require('lodash');
    require('backbone');

    $(document).on('click', '[href]', function(e) {
        e.stopPropagation();

        var $target = $(e.currentTarget);

        if ($target.data('navigate') !== false) {
            e.preventDefault();

            router.navigate($target.attr('href'));
        }
    });

    return _.extend({
            permissions: {},
            apiUrl: null,
            currentPage: null,
            locale: cookie.get('locale'),
            isStarted: false,

            start: function(deps) {
                var app = this;

                requirejs({
                    locale: app.locale
                }, deps, function() {

                    router.start();

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