define(function(require) {
    //requirements
    var get = require('./get');

    require('jquery');

    return function(model, attr) {
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
    }
});