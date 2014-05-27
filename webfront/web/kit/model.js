define(function(require, exports, module) {
    //requirements
    var _ = require('kit/lodash');

    require('backbone');

    return Backbone.Model.extend({
        toJSON: function(options) {
            options = options || {};

            if (options.isSave) {
                return this.getData();
            }

            return Backbone.Model.prototype.toJSON.apply(this, arguments);
        },
        getData: function() {
            var saveData;

            if (_.isFunction(this.saveData)) {
                saveData = this.saveData();
            }

            if (_.isArray(this.saveData)) {
                saveData = _.pick(this.toJSON(), this.saveData);
            }

            return saveData;
        },
        fetch: function(options) {
            return Backbone.Model.prototype.fetch.call(this, _.extend({
                wait: true
            }, options));
        },
        save: function(attributes, options) {
            return Backbone.Model.prototype.save.call(this, attributes, _.extend({
                wait: true,
                isSave: true
            }, options));
        },
        destroy: function(options) {
            Backbone.Model.prototype.destroy.call(this, _.extend({
                wait: true
            }, options))
        }
    })
});