define(function(require, exports, module) {
    //requirements
    var get = require('kit/get/get'),
        config = require('config');

    require('jquery');

    window.LH = _.extend({
        getText: require('kit/getText'),
        modelNode: function(model, attr) {
            var text = typeof model.get(attr) === 'undefined' ? '' : model.get(attr);
            var nodeTemplate = '<span model="' + model.modelName + '" model-id="' + model.id + '" model-attribute="' + attr + '">' + text + '</span>';

            var handlers = {};

            handlers['change:' + attr] = function() {
                $('body')
                    .find('[model-id="' + model.id + '"][model-attribute="' + attr + '"]')
                    .html(model.get(attr) || '');
            };

            model.listenTo(model, handlers);

            return nodeTemplate;
        },
        isAllow: function(){
            return true;
        },
        isReportsAllow: function(){
            return true;
        }
    }, config);
});