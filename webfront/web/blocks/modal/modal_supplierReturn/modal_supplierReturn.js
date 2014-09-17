define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal.deprecated');

    return Modal.extend({
        template: require('ejs!./modal_supplierReturn.ejs'),
        models: {
            supplierReturn: require('models/supplierReturn/supplierReturn')
        },
        partials: {
            form_product: require('ejs!blocks/form/form_product/form_product.ejs'),
            form_supplier: require('ejs!blocks/form/form_supplier/template.ejs')
        },
        events: {
            'click .addSupplierLink': function() {
                var block = this;

                block.showSupplierModal();
            },
            'click .addProductLink': function() {
                var block = this;

                block.showProductModal();
            },
            'click .supplierReturnModalLink': function() {
                var block = this;

                block.showSupplierReturnModal();
            },
            'click .supplierReturn__removeLink': function(e) {
                var block = this;

                e.target.classList.add('loading');

                block.models.supplierReturn.destroy().then(function() {
                    e.target.classList.remove('loading');
                });

            }
        },
        blocks: {
            form_supplierReturn: function(opt) {
                var block = this,
                    Form_supplierReturn = require('blocks/form/form_supplierReturn/form_supplierReturn');

                var form_supplierReturn = new Form_supplierReturn({
                    el: opt.el,
                    model: block.models.supplierReturn
                });

                form_supplierReturn.on('submit:success', function() {

                    block.$el.one('modal.hidden', function(e) {
                        PAGE.render();
                    });
                    
                    block.hide();
                });

                return form_supplierReturn;
            },
            form_supplierReturnProducts: function(opt) {
                var block = this,
                    Form_stockInProducts = require('blocks/form/form_supplierReturnProducts/form_supplierReturnProducts');

                return new Form_stockInProducts({
                    el: opt.el,
                    models: {
                        supplierReturn: block.models.supplierReturn
                    }
                });
            }
        },
        showSupplierReturnModal: function() {
            var block = this;

            block.$('.modal__dialog_supplierReturn')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        },
        showProductModal: function() {
            var block = this;

            block.$('.modal__dialog_product')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        },
        showSupplierModal: function() {
            var block = this;

            block.$('.modal__dialog_supplier')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        },
        renderSupplierSelect: function(supplierModel){
            var block = this,
                select_suppliers = require('ejs!blocks/select/select_suppliers/template.ejs');

            block.$('.select_suppliers').replaceWith(select_suppliers({
                selected: supplierModel.id,
                collections: {
                    suppliers: PAGE.collections.suppliers
                }
            }));
        }
    });
});