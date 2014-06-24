define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        urlRoot: function() {
            return Model.baseApiUrl + '/stores/' + this.get('storeId') + '/products/' + this.get('product.id') + '/returnProducts';
        },
        defaults: {
            storeId: null
        }
    });
});