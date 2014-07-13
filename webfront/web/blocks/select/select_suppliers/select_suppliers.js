define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        SuppliersCollection = require('collections/suppliers'),
        agreementLink = require('ejs!./agreementLink.html');

    return Block.extend({
        el: '.select_suppliers',
        collections: {
            suppliers: new SuppliersCollection()
        },
        selected: null,
        events: {
            'change [name="supplier"]': function(e) {
                var block = this;

                block.renderAgreementLink(e.target.value);
            }
        },
        renderAgreementLink: function(supplierId){
            var block = this;

            $(block.el.querySelector('.select_suppliers__agreementLink')).replaceWith(agreementLink({
                supplierModel: block.collections.suppliers.get(supplierId)
            }))
        }
    });
});