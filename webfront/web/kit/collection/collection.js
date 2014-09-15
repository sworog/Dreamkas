define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        makeClass = require('kit/makeClass/makeClass'),
        deepExtend = require('kit/deepExtend/deepExtend'),
		get = require('kit/get/get'),
        Backbone = require('backbone'),
        _ = require('lodash');

    var Collection = makeClass(Backbone.Collection, {
        initialize: function(data, options){
            _.extend(this, options);
        },
        fetch: function(options) {
			var collection = this;

            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true,
				data: get(collection, 'data')
            }, options));
        }
    });

    Collection.baseApiUrl = config.baseApiUrl;
    Collection.mockApiUrl = config.mockApiUrl;

    return Collection;
});