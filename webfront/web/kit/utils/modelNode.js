define(function(require) {
    //requirements
    var translate = require('./translate'),
        get = require('./get');

    require('jquery');

    return function(model, attr){
        var attrValue = model.get(attr),
            text = translate(get(model, 'dictionary'), typeof attrValue === 'undefined' ? '' : attrValue),
            nodeTemplate = '<span model_name="' + model.modelName + '" model_id="' + model.id + '" model_attr="' + attr + '">' + text + '</span>';

        var handlers = {};

        handlers['change:' + attr] = function(){
            $('body')
                .find('[model_id="' + model.id + '"][model_attr="' + attr + '"]')
                .html(text);
        };

        model.listenTo(model, handlers);

        return nodeTemplate;
    }
});