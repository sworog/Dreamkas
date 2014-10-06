define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal.deprecated');

    return Modal.extend({
        template: require('ejs!./modal_stockIn.ejs'),
        models: {
            stockIn: require('resources/stockIn/model')
        },
        partials: {
            form_product: require('ejs!blocks/form/form_product/form_product.ejs')
        },
        events: {
            'click .addProductLink': function() {
                var block = this;

                block.showProductModal();
            },
            'click .stockInModalLink': function() {
                var block = this;

                block.showStockInModal();
            },
            'click .stockIn__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.stockIn.destroy().then(function() {
                    e.target.classList.remove('loading');
                });

            }
        },
        blocks: {
            form_stockIn: function(opt){
                var block = this,
                    Form_stockIn = require('blocks/form/form_stockIn/form_stockIn');

                var form_stockIn = new Form_stockIn({
                    el: opt.el,
                    model: block.models.stockIn
                });

                form_stockIn.on('submit:success', function(){
                    block.$el.one('modal.hidden', function(e) {
                        PAGE.render();
                    });

                    block.hide();
                });

                return form_stockIn;
            },
            form_stockInProducts: function(opt){
                var block = this,
                    Form_stockInProducts = require('blocks/form/form_stockInProducts/form_stockInProducts');

                return new Form_stockInProducts(_.extend(opt, {
                    models: {
                        stockIn: block.models.stockIn
                    }
                }));
            },
            form_product: function(opt) {
                var block = this,
                    ProductModel = require('resources/product/model'),
                    Form_product = require('blocks/form/form_product/form_product'),
                    form_product = new Form_product({
                        el: opt.el
                    });

                form_product.on('submit:success', function(){
                    block.el.querySelector('.form_stockInProducts').block.renderSelectedProduct(form_product.model.toJSON());
                    block.showStockInModal();
                    form_product.model = new ProductModel();
                    form_product.clear();
                });

                return form_product;
            }
        },
        showStockInModal: function() {
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