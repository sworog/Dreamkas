define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'writeOffProduct',
        urlRoot: function() {
            return LH.baseApiUrl + '/writeoffs/' + this.get('writeOff').id + '/products';
        },
        saveData: [
            'product',
            'quantity',
            'price',
            'cause'
        ]
    });
});