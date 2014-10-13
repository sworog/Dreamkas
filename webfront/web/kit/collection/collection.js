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

            options = _.extend({
                reset: true,
                filters: {},
                data: {}
            }, options);

            set(this, 'filters', options.filters);

            options.data = deepExtend({}, this.filters, options.data);

            this.request && this.request.abort();

            this.request = Backbone.Collection.prototype.fetch.call(this, options);

            return this.request;
        }
    });

    Collection.baseApiUrl = config.baseApiUrl;
    Collection.mockApiUrl = config.mockApiUrl;

    return Collection;
});