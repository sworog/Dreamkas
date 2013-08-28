define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        _ = require('underscore'),
        getter = require('kit/utils/getter');

    /**
     * Расширение <a target="blank" href="http://backbonejs.org/#Model">Backbone.Model</a><br />
     * Содержит дополнительные свойства и методы
     *
     * @class model
     * @constructor
     */
    return Backbone.Model.extend({
        saveData: null,
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
            var model = this;

            return getter.get.apply(model.attributes, arguments);
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

            if (options.isSave) {
                return this.getData();
            }

            return Backbone.Model.prototype.toJSON.apply(this, arguments);
        },
        getData: function(){
            var saveData;

            if (_.isFunction(this.saveData)){
                saveData = this.saveData();
            }

            if (_.isArray(this.saveData)){
                saveData = _.pick(this.toJSON(), this.saveData);
            }

            return saveData;
        }
    })
});