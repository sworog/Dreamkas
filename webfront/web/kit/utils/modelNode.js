define(function(require) {
    //requirements
    var getText = require('./translate'),
        get = require('./get');

    require('jquery');

    return function(model, attr) {
        var nodeTemplate = '<span model="' + model.modelName + '" model-id="' + model.id + '" model-attr="' + attr + '">' + (model.get(attr) || '') + '</span>';

        var handlers = {};

        handlers['change:' + attr] = function() {
            $('body')
                .find('[model-id="' + model.id + '"][model-attr="' + attr + '"]')
                .html(model.get(attr) || '');
        };

        model.listenTo(model, handlers);

        return nodeTemplate;
    }
});