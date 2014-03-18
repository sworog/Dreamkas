define(function(require) {
    //requirements
    var Backbone = require('backbone');

    require('lodash');

    return Backbone.Collection.extend({
        element: require('kit/collectionNode/collectionNode'),
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));
        }
    });
});