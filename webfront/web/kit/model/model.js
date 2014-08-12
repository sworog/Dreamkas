define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        get = require('kit/get/get'),
        _ = require('lodash'),
        makeClass = require('kit/makeClass/makeClass'),
        Backbone = require('backbone');

    var Model = makeClass(Backbone.Model, {
        constructor: function(attributes, options){

            options = _.extend({
                parse: true
            }, options);

            Backbone.Model.call(this, attributes, options);
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
        save: function(attributes, options) {
            return Backbone.Model.prototype.save.call(this, attributes, _.extend({
                wait: true,
                isSave: true
            }, options));
        },
        destroy: function(options) {
            return Backbone.Model.prototype.destroy.call(this, _.extend({
                wait: true
            }, options))
        },
        get: function(path) {
            return get(this, 'attributes.' + path);
        },
        element: function(attr) {
            var model = this,
                uniqueId = _.uniqueId('modelElement'),
                nodeTemplate = '<span id="' + uniqueId + '">' + (_.escape(model.get(attr)) || '') + '</span>';

            model.on('change:' + attr, function() {
                document.getElementById(uniqueId).innerHTML = _.escape(model.get(attr)) || '';
            });

            return nodeTemplate;
        },
        parse: function(data) {
            var model = this;

            _.forEach(model.collections, function(collection, key) {

                if (typeof collection === 'function'){
                    model.collections[key] = collection.call(model);
                }

                if (model.collections[key] instanceof Backbone.Collection){
                    model.collections[key].reset(data[key]);
                }
            });

            return data;
        }
    });

    Model.baseApiUrl = config.baseApiUrl;
    Model.mockApiUrl = config.mockApiUrl;

    return Model;
});