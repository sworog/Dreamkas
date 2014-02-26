define(function(require) {
    //requirements
    var BaseClass = require('utils/baseClass');

    require('backbone');

    return Backbone.Collection.extend({
        node: require('utils/collectionNode'),
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));
        }
    });
});