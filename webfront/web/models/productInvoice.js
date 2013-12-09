define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser');

    var templates = {
        amount: require('tpl!blocks/amount/amount.html')
    };

    require('moment');

    return Model.extend({
        modelName: 'productInvoice',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/products/' + this.get('product').id + '/invoiceProducts';
        },
        defaults: {
            acceptanceDateFormatted: compute(['acceptanceDate'], function(acceptanceDate){
                return moment(acceptanceDate).format('DD.MM.YYYY');
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