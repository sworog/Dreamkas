define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./modal_supplierReturn.ejs'),
        models: {
            supplierReturn: require('models/stockIn/stockIn')
        },
        partials: {
            form_product: require('ejs!blocks/form/form_product/form_product.ejs')
        },
        events: {
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
            },
            form_product: function(opt) {
                var block = this,
                    ProductModel = require('models/product/product'),
                    Form_product = require('blocks/form/form_product/form_product'),
                    form_product = new Form_product({
                        el: opt.el
                    });

                form_product.on('submit:success', function() {
                    block.el.querySelector('.form_stockInProducts').block.renderSelectedProduct(form_product.model.toJSON());
                    block.showSupplierReturnModal();
                    form_product.model = new ProductModel();
                    form_product.clear();
                });

                return form_product;
            }
        },
        showSupplierReturnModal: function() {
            var block = this;

            block.$('.modal__dialog_stockIn')
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
        }
    });
});