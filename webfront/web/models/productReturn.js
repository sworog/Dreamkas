define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser');

    require('moment');

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
                return LH.formatPrice(totalPrice);
            }),
            priceFormatted: compute(['price'], function(price){
                return LH.formatPrice(price);
            }),
            quantityElement: compute(['quantity'], function(quantity){
                return String.prototype.split.call(quantity, '.')[0] + '<span class="layout__floatPart">,' + (String.prototype.split.call(quantity, '.')[1] || '00') + '</span>'
            })
        }
    });
});