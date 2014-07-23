define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/products',
        defaults: {
            vat: 0
        },
        saveData: function() {

            var model = this;

            return {
                subCategory: model.get('subCategory'),
                name: model.get('name'),
                units: model.get('units'),
                barcode: model.get('barcode'),
                vat: model.get('vat'),
                purchasePrice: normalizeNumber(model.get('purchasePrice')),
                sellingPrice: normalizeNumber(model.get('sellingPrice')),
                type: 'unit'
            }
        }
    });
});
