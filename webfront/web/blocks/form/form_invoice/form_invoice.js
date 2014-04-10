define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        currentUserModel = require('models/currentUser'),
        SuppliersCollection = require('collections/suppliers'),
        InputDate = require('blocks/inputDate/inputDate'),
        Select_suppliers = require('blocks/select/select_suppliers/select_suppliers'),
        router = require('router');

    return Form.extend({
        redirectUrl: function() {
            return '/' + currentUserModel.stores.at(0).id + '/invoices';
        },
        el: '.form_invoice',
        collections: {
            suppliers: new SuppliersCollection()
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            new InputDate();

            new Select_suppliers({
                collections: _.pick(block.collections, 'suppliers')
            });
        }
    });
});