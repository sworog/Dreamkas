define(function(require) {
    //requirements
    var $ = require('jquery'),
        Backbone = require('backbone'),
        _ = require('underscore');

    require('utils');
    require('nls');
    require('jquery.cookie');

    $(function() {
        var router = new Backbone.Router();

        $('body').on('click', '[href]', function(e) {
            e.preventDefault();
            router.navigate($(this).attr('href'), {
                trigger: true
            });
        });
    });

    return {
        start: function() {
            Backbone.history.start({
                pushState: true
            });
        },
        logout: function() {
            $.removeCookie('token');
            document.location.reload();
        }
    }
});