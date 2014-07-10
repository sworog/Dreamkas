define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        get = require('kit/get/get'),
        _ = require('lodash');

    require('backbone');

    var Model = Backbone.Model.extend({
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
            Backbone.Model.prototype.destroy.call(this, _.extend({
                wait: true
            }, options))
        },
        get: function(path) {
            return get(this, 'attributes.' + path);
        },
        element: function(attr){
            var model = this,
                uniqueId = _.uniqueId('modelElement'),
                nodeTemplate = '<span id="' + uniqueId + '">' + _.escape(model.get(attr)) || '' + '</span>';

            model.on('change:' + attr, function() {
                document.getElementById(uniqueId).innerHTML = _.escape(model.get(attr)) || '';
            });

            return nodeTemplate;
        }
    }).extend();

    Model.baseApiUrl = config.baseApiUrl;
    Model.mockApiUrl = config.mockApiUrl;

    return Model;
});