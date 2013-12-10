define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser');

    var templates = {
        amount: require('tpl!blocks/amount/amount.html')
    };

    return Model.extend({
        modelName: 'writeOffProduct',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/writeoffs/' + this.get('writeOff').id + '/products';
        },
        saveData: [
            'product',
            'quantity',
            'price',
            'cause'
        ],
        defaults: {
            quantityElement: compute(['quantity'], function(quantity){
                return templates.amount({value: quantity});
            })
        }
    });
});