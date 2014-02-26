define(function(require) {
    //requirements
    var get = require('utils/get'),
        BaseClass = require('utils/baseClass');

    require('lodash');
    require('backbone');

    /**
     * Расширение <a target="blank" href="http://backbonejs.org/#Model">Backbone.Model</a><br />
     * Содержит дополнительные свойства и методы
     *
     * @class model
     * @constructor
     */
    var Model = Backbone.Model.extend({
        saveData: null,
        nestedData: {},
        node: require('utils/modelNode'),
        dictionary: null,
        constructor: function() {
            var model = this;

            Backbone.Model.apply(model, arguments);

            this.listenTo(this, {
                change: function(model, options) {

                    var changedAttributes = model.changed;

                    _.each(changedAttributes, function(changedAttrValue, changedAttrKey) {
                        if (model.nestedData[changedAttrKey] && model[changedAttrKey]) {
                            model[changedAttrKey].set(changedAttrValue);
                        }
                    });

                    _.each(model.attributes, function(attrValue, attrKey) {
                        if (typeof attrValue === 'function' && _.intersection(attrValue.__dependencies__, _.keys(changedAttributes)).length) {
                            model.trigger('change:' + attrKey, model, model.get(attrKey), options);
                        }
                    });
                }
            });

            _.each(model.nestedData, function(Class, key) {
                model[key] = new Class(model.get(key), {
                    parentModel: model,
                    parse: true
                });

                model.listenTo(model[key], {
                    change: function() {
                        model.set(key, model[key].toJSON());
                    }
                })
            });

        },
        get: function() {
            arguments[0] = 'attributes.' + arguments[0];
            return BaseClass.prototype.get.apply(this, arguments);
        },
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
        },
        getText: function(){
            var args = [this.dictionary].concat([].slice.call(arguments));
            return getText.apply(window, args);
        }
    });

    Model.compute = require('utils/compute');

    return Model;
});