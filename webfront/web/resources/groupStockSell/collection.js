define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            groupId: null,
            url: function(){
                return Collection.baseApiUrl + '/catalog/groups/' + this.groupId + '/reports/grossMarginSalesByProduct'
            }
        });
    }
);