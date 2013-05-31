define(
    [
        './baseCollection.js',
        '/models/writeOffProduct.js'
    ],
    function(BaseCollection, writeOffProduct) {
        return BaseCollection.extend({
            model: writeOffProduct,

            initialize: function(opt){
                this.writeOffId = opt.writeOffId;
            },
            url: function() {
                return baseApiUrl + '/writeoffs/'+ this.writeOffId  + '/products'
            }
        });
    }
);