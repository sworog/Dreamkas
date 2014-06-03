define(function(require, exports, module) {
    //requirements
    var config = require('config');

    require('backbone');

    var Collection = Backbone.Collection.extend({
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));
        }
    });

    Collection.baseApiUrl = config.baseApiUrl;
    Collection.mockApiUrl = config.mockApiUrl;


    return Collection;
});