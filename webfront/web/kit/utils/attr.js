define(function(require) {
    //requirements
    var text = require('kit/utils/text');

    return KIT.attr = function(model, attr){
        return '<span model_name="' + model.modelName + '" model_id="' + model.id + '" model_attr="' + attr + '">' + text(model.get(attr)) + '</span>';
    }
});