define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/cashFlows',
        defaults: {
            direction: 'out'
        },
        saveData: function(){

            return {
                direction: this.get('direction'),
                data: this.get('date'),
                amount: this.get('amount'),
                comment: this.get('comment')
            }
        }
    });
});