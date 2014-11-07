define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
        _ = require('lodash');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/products',
        defaults: {
            vat: 0,
            units: 'шт'
        },
        saveData: function() {

            var model = this,
                purchasePrice = normalizeNumber(model.get('purchasePrice')),
                sellingPrice = normalizeNumber(model.get('sellingPrice'));

            return {
                subCategory: model.get('subCategory'),
                name: model.get('name'),
                units: model.get('units'),
                barcode: model.get('barcode'),
                vat: model.get('vat'),
                purchasePrice: _.isNaN(purchasePrice) ? model.get('purchasePrice') : purchasePrice,
                sellingPrice: _.isNaN(sellingPrice) ? model.get('sellingPrice') : sellingPrice,
                type: 'unit'
            }
        }
    });
});
