define(function(require) {
    //requirements
    var Collection = require('kit/collection');

    return Collection.extend({
        model: require('models/user'),
        comparator: 'name',
        url: function(){
            return '/stores/' + this.storeId + '/managers?candidates=1';
        },
        initialize: function([], options){
            this.storeId = options.storeId;
        }
    });
});