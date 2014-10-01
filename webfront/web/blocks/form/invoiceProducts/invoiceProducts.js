define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: require('resources/invoiceProduct/model'),
        collection: require('resources/invoiceProduct/collection'),
        blocks: {
            productList: function(){
                var ProductList = require('./productList');

                return new ProductList({
                    collection: this.collection
                });
            }
        }
    });
});