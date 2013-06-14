define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    return BaseModel.extend({
            modelName: 'writeOffProduct',
            urlRoot: function(){
                return LH.baseApiUrl + '/writeoffs/'+ this.get('writeOff').id  + '/products';
            },
            saveFields: [
                'product',
                'quantity',
                'price',
                'cause'
            ]
        });
    });