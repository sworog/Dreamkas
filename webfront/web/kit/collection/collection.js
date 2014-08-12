define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        makeClass = require('kit/makeClass/makeClass'),
        Backbone = require('backbone'),
        _ = require('lodash');

    require('backbone');

    var Collection = makeClass(Backbone.Collection, {
        initialize: function(data, options){
            _.extend(this, options);
        },
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