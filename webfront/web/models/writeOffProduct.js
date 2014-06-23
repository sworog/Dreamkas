define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/computeAttr/computeAttr'),
        currentUserModel = require('models/currentUser.inst'),
        numeral = require('numeral');

    var templates = {
        amount: require('ejs!blocks/amount/amount.html')
    };

    return Model.extend({
        modelName: 'writeOffProduct',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/writeoffs/' + this.get('writeOff').id + '/products';
        },
        saveData: function(){
            return {
                product: this.get('product'),
                quantity: this.get('quantity'),
                price: this.get('price'),
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