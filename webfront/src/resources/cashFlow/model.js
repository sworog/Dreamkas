define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/cashFlows',
        defaults: {
            direction: 'out'
        },
        saveData: function(){

            return {
                direction: this.get('direction'),
                date: this.get('date'),
                amount: normalizeNumber(this.get('amount')),
                comment: this.get('comment')
            }
        }
    });
});