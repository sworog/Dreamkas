define(function(require) {
    //requirements
    var Collection = require('kit/collection/collection'),
        InvoiceModel = require('models/invoice/invoice'),
        WriteOffModel = require('models/writeOff/writeOff');

    return Collection.extend({
        filterTypes: '',
        url: function(){
            var filterTypesString = '';
            if ('' != this.filterTypes) {
                filterTypesString = 'types=' + this.filterTypes;
            }
            return Collection.baseApiUrl + '/stockMovements?' + filterTypesString;
        },
        parse: function(data) {
            var collection = this;
            data.forEach(function(item) {
                switch (item.type) {
                    case "Invoice":
                        collection.add(new InvoiceModel(item));
                        break;

                    case "WriteOff":
                        collection.add(new WriteOffModel(item));
                        break;
                }
            });
        }
    });
});