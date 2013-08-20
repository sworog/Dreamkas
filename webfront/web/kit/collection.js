define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        _ = require('underscore');

    return Backbone.Collection.extend({
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));
        }
    });
});