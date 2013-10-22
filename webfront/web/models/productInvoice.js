define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        computeAttr = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser');

    require('moment');

    return Model.extend({
        modelName: 'productInvoice',
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/products/' + this.get('product').id + '/invoiceProducts';
        },
        defaults: {
            acceptanceDateFormatted: computeAttr(['acceptanceDate'], function(acceptanceDate){
                return moment(acceptanceDate).format('DD.MM.YYYY');
            })
        }
    });
});