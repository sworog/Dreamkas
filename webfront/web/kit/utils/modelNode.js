define(function(require) {
    //requirements
    var getText = require('./getText'),
        get = require('./get');

    require('jquery');

    function ensureAttr(model, attr) {
        var attrValue = model.get(attr);

        return getText(get(model, 'dictionary'), typeof attrValue === 'undefined' ? '' : attrValue);
    }

    return function(model, attr) {
        var text = ensureAttr(model, attr),
            nodeTemplate = '<span model_name="' + model.modelName + '" model_id="' + model.id + '" model_attr="' + attr + '">' + text + '</span>';

        var handlers = {};

        handlers['change:' + attr] = function() {
            var text = ensureAttr(model, attr);

            $('body')
                .find('[model_id="' + model.id + '"][model_attr="' + attr + '"]')
                .html(text);
        };

        model.listenTo(model, handlers);

        return nodeTemplate;
    }
});