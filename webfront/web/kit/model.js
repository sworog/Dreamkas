define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        _ = require('underscore');

    return Backbone.Model.extend({
        saveFields: [],
        initData: {},
        constructor: function() {
            var model = this;

            Backbone.Model.apply(model, arguments);

            this.listenTo(this, {
                change: function(model){
                    var changedAttributes = model.changed;

                    _.each(changedAttributes, function(value, key) {
                        $('body')
                            .find('[model_id="' + model.id + '"][model_attr="' + key + '"]')
                            .html(value);

                        if (model.initData[key] && model[key]) {
                            model[key].set(value);
                        }
                    });
                }
            });

            _.each(model.initData, function(Class, key) {
                model[key] = new Class(model.get(key), {
                    parentModel: model,
                    parse: true
                });

                model.listenTo(model[key], {
                    change: function(){
                        model.set(key, model[key].toJSON());
                    }
                })
            });

        },
        get: function(path){
            var model = this,
                segments = path.split('.'),
                attr = model.attributes;

            _.every(segments, function(segment){
                return attr = attr[segment];
            });

            return attr;
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
        },
        toJSON: function(options) {
            options = options || {};

            var toJSON = Backbone.Model.prototype.toJSON;

            if (options.isSave) {
                return _.pick(toJSON.apply(this, arguments), this.saveFields);
            }

            return toJSON.apply(this, arguments);
        }
    })
});