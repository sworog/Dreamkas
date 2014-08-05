define(function(require) {
    //requirements
    var Model = require('kit/core/model');

    var RoundingsModel = Model.extend({
        modelName: 'roundings',
        urlRoot: LH.baseApiUrl + '/roundings'
    });

    var roundingsModel = new RoundingsModel();

    roundingsModel.fetch({
        success: function(){
            console.log(roundingsModel);
        }
    });

    return roundingsModel;
});