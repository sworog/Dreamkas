define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            groupId: null,
            model: require('kit/model/model'),
            url: function(){
                return Collection.baseApiUrl + '/catalog/groups/' + this.groupId + '/reports/grossMarginSalesByProduct'
            }
        });
    }
);