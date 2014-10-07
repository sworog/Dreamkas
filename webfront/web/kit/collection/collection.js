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
        filters: {},
        initialize: function(data, options){

            this.filters = get(this, 'filters');

            deepExtend(this, options);
        },
        fetch: function(options) {

            options = deepExtend({
                data: this.filters
            }, options);

            this.request && this.request.abort();

            this.request = Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));

            return this.request;
        },
        filter: function(filters){
            set(this, 'filters', filters);

            return this.fetch();
        }
    });

    Collection.baseApiUrl = config.baseApiUrl;
    Collection.mockApiUrl = config.mockApiUrl;

    return Collection;
});