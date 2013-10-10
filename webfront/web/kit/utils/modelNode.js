define(function(require) {
    //requirements
    var translate = require('./translate'),
        get = require('./get');

    require('jquery');

    return function(model, attr){
        var nodeTemplate = '<span model_name="' + model.modelName + '" model_id="' + model.id + '" model_attr="' + attr + '">' + translate(get(model, 'dictionary'), model.get(attr)) + '</span>';

        var handlers = {};

        handlers['change:' + attr] = function(){
            $('body')
                .find('[model_id="' + model.id + '"][model_attr="' + attr + '"]')
                .html(translate(get(model, 'dictionary'), model.get(attr)));
        };

        model.listenTo(model, handlers);

        return nodeTemplate;
    }
});