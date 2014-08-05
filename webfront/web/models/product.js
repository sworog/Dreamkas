define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        BarcodesCollections = require('collections/barcodes'),
        numeral = require('numeral');

    return Model.extend({
        urlRoot: function(){
            if (this.get('storeId')){
                return Model.baseApiUrl + '/stores/' + this.get('storeId') + '/products';
            } else {
                return Model.baseApiUrl + '/products';
            }
        },
        defaults: {
            amount: 0,
            retailPricePreference: 'retailMarkup',
            rounding: {},
            type: 'unit',
            storeId: null,
            subCategoryId: null
        },
        initialize: function(){
            this.collections = {
                barcodes: new BarcodesCollections(this.get('barcodes'), {
                    productId: this.id
                })
            };
        },
        saveData: function() {

            var purchasePrice = parseFloat((this.get('purchasePrice') || '').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi')),
                retailPriceMin = parseFloat((this.get('retailPriceMin') || '').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi')),
                retailPriceMax = parseFloat((this.get('retailPriceMax') || '').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi')),
                retailMarkupMax = parseFloat((this.get('retailMarkupMax') || '').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi')),
                retailMarkupMin = parseFloat((this.get('retailMarkupMin') || '').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi'));

            if (!purchasePrice && this.get('purchasePrice') !== '0') {
                purchasePrice = this.get('purchasePrice');
            }

            if (!retailPriceMin && this.get('retailPriceMin') !== '0') {
                retailPriceMin = this.get('retailPriceMin');
            }

            if (!retailPriceMax && this.get('retailPriceMax') !== '0') {
                retailPriceMax = this.get('retailPriceMax');
            }

            if (!retailMarkupMax && this.get('retailMarkupMax') !== '0') {
                retailMarkupMax = this.get('retailMarkupMax');
            }

            if (!retailMarkupMin && this.get('retailMarkupMin') !== '0') {
                retailMarkupMin = this.get('retailMarkupMin');
            }

            if (this.get('storeId')){
                return {
                    retailPrice: this.get('retailPrice'),
                    retailMarkup: this.get('retailMarkup'),
                    retailPricePreference: this.get('retailPricePreference')
                };
            } else {
                return {
                    name: this.get('name'),
                    vat: this.get('vat'),
                    purchasePrice: purchasePrice,
                    retailPriceMin: retailPriceMin,
                    retailPriceMax: retailPriceMax,
                    retailMarkupMax: retailMarkupMax,
                    retailMarkupMin: retailMarkupMin,
                    retailPricePreference: this.get('retailPricePreference'),
                    barcode: this.get('barcode'),
                    vendorCountry: this.get('vendorCountry'),
                    vendor: this.get('vendor'),
                    subCategory: this.get('subCategoryId'),
                    rounding: this.get('rounding') ? this.get('rounding.name') : null,
                    type: this.get('type'),
                    typeProperties: this.get('type') === 'unit' ? null : this.get('typeProperties')
                };
            }
        },
        calculateRanges: function(){
            var model = this,
                purchasePrice = model.get('purchasePrice'),
                retailMarkupMin = model.get('retailMarkupMin'),
                retailMarkupMax = model.get('retailMarkupMax'),
                retailPriceMin = model.get('retailPriceMin'),
                retailPriceMax = model.get('retailPriceMax');

            switch (model.get('retailPricePreference')){
                case 'retailMarkup':

                    model.set({
                        retailPriceMin: retailMarkupMin ? (purchasePrice + purchasePrice * retailMarkupMin / 100) : null,
                        retailPriceMax: retailMarkupMax ? (purchasePrice + purchasePrice * retailMarkupMax / 100) : null
                    });

                    break;
                case 'retailPrice':

                    model.set({
                        retailMarkupMin: retailPriceMin ? (100 * (retailPriceMin - purchasePrice) / purchasePrice) : null,
                        retailMarkupMax: retailPriceMax ? (100 * (retailPriceMax - purchasePrice) / purchasePrice) : null
                    });

                    break;
            }

        },
        parse: function(){
            var data = Model.prototype.parse.apply(this, arguments);

            if (this.collections){
                this.collections.barcodes.reset(data.barcodes);
            }

            return data;
        }
    });
});
