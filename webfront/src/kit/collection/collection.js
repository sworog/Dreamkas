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
        original: null,
        filters: {},
        model: require('kit/model/model'),
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
        },
        add: function(item, options) {
            options = options || {};

            if (options.temp) {
                this.original = this.original || this.clone();
            }

            return Backbone.Collection.prototype.add.apply(this, arguments);
        },
        remove: function(item, options) {
            options = options || {};

            if (options.temp) {
                this.original = this.original || this.clone();
            }

            return Backbone.Collection.prototype.remove.apply(this, arguments);
        },
        applyChanges: function() {
            this.original = null;
        },
        isChanged: function() {
            return this.original != null;
        },
        restore: function() {
            if (this.original) {
                this.reset(this.original.models);
                this.original = null;
            }
        }
    });

    Collection.baseApiUrl = config.baseApiUrl;
    Collection.mockApiUrl = config.mockApiUrl;

    return Collection;
});