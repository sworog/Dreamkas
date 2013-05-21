define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
        return baseModel.extend({
            modelName: 'writeOffProduct',
            urlRoot: function(){
                return baseApiUrl + '/writeoffs/'+ this.get('writeOff').id  + '/products';
            },
            saveFields: [
                'product',
                'quantity',
                'price',
                'cause'
            ]
        });
    });