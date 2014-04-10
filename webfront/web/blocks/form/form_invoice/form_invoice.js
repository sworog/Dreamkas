define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        InvoiceModel = require('models/invoice'),
        currentUserModel = require('models/currentUser'),
        SuppliersCollection = require('collections/suppliers'),
        InputDate = require('blocks/inputDate/inputDate'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        Select_suppliers = require('blocks/select/select_suppliers/select_suppliers'),
        router = require('router');

    return Form.extend({
        redirectUrl: function() {
            return '/' + currentUserModel.stores.at(0).id + '/invoices';
        },
        el: '.form_invoice',
        storeId: null,
        listeners: {
            'blocks.autocomplete': {
                select: function(product) {
                    var block = this;

                    block.model.get('collections.products').push({
                        quantity: 1,
                        product: product
                    });
                }
            }
        },
        model: new InvoiceModel(),
        collections: {
            suppliers: new SuppliersCollection()
        },
        'change [name]': function(e) {
            var block = this;

            block.model.set(e.target.name, e.target.value);
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.model = block.get('model');

            block.blocks = {
                autocomplete: new Autocomplete(),
                inputDate: new InputDate(),
                select_suppliers: new Select_suppliers({
                    collections: _.pick(block.collections, 'suppliers')
                })
            };
        }
    });
});