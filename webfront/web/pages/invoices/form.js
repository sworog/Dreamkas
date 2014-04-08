define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        InvoiceModel = require('models/invoice'),
        Form_invoice = require('blocks/form/form_invoice/form_invoice'),
        currentUserModel = require('models/currentUser'),
        InputDate = require('blocks/inputDate/inputDate'),
        InputDate_noTime = require('blocks/inputDate/inputDate_noTime'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_invoice_form',
        partials: {
            '#content': require('tpl!./templates/form.html')
        },
        initialize: function(pageParams) {
            var page = this;

            if (!LH.isAllow('stores/{store}/invoices', 'POST')){
                new Page403();
                return;
            }

            if (currentUserModel.stores.length){
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId){
                new Page403();
                return;
            }

            page.invoiceModel = new InvoiceModel({
                storeId: pageParams.storeId
            });

            page.render();

            new Form_invoice({
                model: page.invoiceModel,
                el: document.getElementById('form_invoice')
            });

            new InputDate({
                el: page.el.querySelector('[name="acceptanceDate"]')
            });

            new InputDate_noTime({
                el: page.el.querySelector('[name="supplierInvoiceDate"]')
            });
        }
    });
});