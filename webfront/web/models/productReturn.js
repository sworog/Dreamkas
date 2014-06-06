define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/computeAttr/computeAttr'),
        currentUserModel = require('models/currentUser.inst'),
        moment = require('moment');

    var templates = {
        amount: require('tpl!blocks/amount/amount.html')
    };

    return Model.extend({
        modelName: 'productReturn',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/products/' + this.get('product').id + '/returnProducts';
        },
        defaults: {
            createdDateFormatted: compute(['createdDate'], function(createdDate){
                return moment(createdDate).format('DD.MM.YYYY');
            }),
            totalPriceFormatted: compute(['totalPrice'], function(totalPrice){
                return LH.formatMoney(totalPrice);
            }),
            priceFormatted: compute(['price'], function(price){
                return LH.formatMoney(price);
            }),
            quantityElement: compute(['quantity'], function(quantity){
                return templates.amount({value: quantity});
            })
        }
    });
});