define(function(require) {
        //requirements
        var Block = require('kit/core/block.deprecated'),
            Form_purchase = require('blocks/form/form_purchase/form_purchase'),
            Form_purchaseProduct = require('blocks/form/form_purchaseProduct/form_purchaseProduct');

        return Block.extend({
            __name__: 'saleBox',
            template: require('ejs!blocks/saleBox/templates/index.html'),
            templates: {
                index: require('ejs!blocks/saleBox/templates/index.html'),
                form_purchase: require('ejs!blocks/form/form_purchase/templates/index.html'),
                form_purchaseProduct: require('ejs!blocks/form/form_purchaseProduct/templates/index.html')
            },
            events: {
                'submit #form_purchaseProduct': function(){
                    var block = this;
                    block.$productRow.clone().appendTo(block.$purchaseTableBody);
                    block.form_purchaseProduct.clear();
                },
                'click .saleBox__removeProductLink': function(e) {
                    var $target = $(e.target);

                    $target.closest('.saleBox__productRow').remove();
                }
            },
            initialize: function(){
                var block = this;

                block.form_purchase = new Form_purchase({
                    el: document.getElementById('form_purchase')
                });

                block.form_purchaseProduct = new Form_purchaseProduct({
                    el: document.getElementById('form_purchaseProduct')
                });
            },
            findElements: function(){
                var block = this;

                block.$productRow = block.$('#form_purchaseProduct').find('.saleBox__productRow');
                block.$purchaseTableBody = block.$('.saleBox__purchaseTable').find('tbody');
            }
        });
    }
);