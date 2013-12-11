define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser'),
        numeral = require('libs/numeral');

    var templates = {
        amount: require('tpl!blocks/amount/amount.html')
    };

    return Model.extend({
        modelName: 'writeOffProduct',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/writeoffs/' + this.get('writeOff').id + '/products';
        },
        saveData: function(){
            return {
                product: this.get('product'),
                quantity: numeral().unformat(LH.formatAmount(this.get('quantity'))),
                price: numeral().unformat(LH.formatMoney(this.get('price'))),
                cause: this.get('cause')
            }
        },
        defaults: {
            quantityElement: compute(['quantity'], function(quantity){
                return templates.amount({value: quantity});
            })
        }
    });
});