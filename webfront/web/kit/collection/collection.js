define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        makeClass = require('kit/makeClass/makeClass'),
        deepExtend = require('kit/deepExtend/deepExtend'),
		get = require('kit/get/get'),
        set = require('kit/set/set'),
        Backbone = require('backbone'),
        _ = require('lodash');

    var Collection = makeClass(Backbone.Collection, {
        initialize: function(data, options){
            _.extend(this, options);
        },
        fetch: function(options) {

            options = deepExtend({});

            this.request && this.request.abort();

            this.request = Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));

            return this.request;
        },
        get: function() {
            var args = [this].concat([].slice.call(arguments));

            return get.apply(null, args);
        },

        set: function() {
            var args = [this].concat([].slice.call(arguments));

            return set.apply(null, args);
        },
        filter: function(filters){
            this.set('filters', filters);

            return this.fetch();
        }
    });

    Collection.baseApiUrl = config.baseApiUrl;
    Collection.mockApiUrl = config.mockApiUrl;

    return Collection;
});