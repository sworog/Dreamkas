define(function(require) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('models/user'),
        url: function(){
            return '/stores/' + this.storeId + '/managers';
        },
        initialize: function([], options){
            this.storeId = options.storeId;
        }
    });
});