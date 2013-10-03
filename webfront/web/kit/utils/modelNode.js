define(function(require) {
    //requirements
    var text = require('./text');

    require('jquery');

    return function(model, attr){
        var nodeTemplate = '<span model_name="' + model.modelName + '" model_id="' + model.id + '" model_attr="' + attr + '">' + text(model.get(attr)) + '</span>';

        var handlers = {};

        handlers['change:' + attr] = function(){
            $('body')
                .find('[model_id="' + model.id + '"][model_attr="' + attr + '"]')
                .html(text(model.get(attr)));
        };

        model.listenTo(model, handlers);

        return nodeTemplate;
    }
});