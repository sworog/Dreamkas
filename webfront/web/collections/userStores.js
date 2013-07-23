define(function(require) {
    //requirements
    var Collection = require('kit/collection'),
        StoreModel = require('models/store');

    return Collection.extend({
        model: StoreModel,
        url: function(){
            return LH.baseApiUrl + '/users/' + this.userId + '/stores';
        },
        initialize: function(models, options){
            this.userId = options.userId;
        }
    });
});