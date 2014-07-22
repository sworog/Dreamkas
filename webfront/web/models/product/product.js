define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        numeral = require('numeral');

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
                purchasePrice: model.get('purchasePrice'),
                sellingPrice: model.get('sellingPrice'),
                type: 'unit'
            }
        }
    });
});
