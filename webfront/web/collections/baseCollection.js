define(function(require) {
    var cookie = require('utils/cookie'),
        Backbone = require('backbone'),
        _ = require('underscore');

    return Backbone.Collection.extend({
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true,
                error: function() {
                    console.log(arguments)
                }
            }, options));
        },
        sync: function(method, model, options) {
            return Backbone.Collection.prototype.sync.call(this, method, model, _.extend({
                headers: {
                    Authorization: 'Bearer ' + cookie.get('token')
                }
            }, options));
        }
    });
});