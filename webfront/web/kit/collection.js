define(function(require, exports, module) {
    //requirements
    require('backbone');

    return Backbone.Collection.extend({
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));
        }
    });
});