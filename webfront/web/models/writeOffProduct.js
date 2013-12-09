define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser');

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
                return String.prototype.split.call(quantity, '.')[0] + '<span class="layout__floatPart">,' + (String.prototype.split.call(quantity, '.')[1] || '000') + '</span>'
            })
        }
    });
});