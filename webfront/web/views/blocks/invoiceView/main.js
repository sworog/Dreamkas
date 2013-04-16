define(
    [
        '/models/invoice.js',
        '/collections/invoiceProducts.js',
        '/utils/main.js',
        'tpl!./head.html',
        'tpl!./footer.html',
        'tpl!./table.html',
        'tpl!./row.html'
    ],
    function(invoiceModel, invoiceProductsCollection, utils, headTpl, footerTpl, tableTpl, rowTpl) {
        return Backbone.Block.extend({
            initialize: function() {
                var block = this;

                block.$head = block.$el.find('.invoice__head');
                block.$table = block.$el.find('.invoice__table');
                block.$footer = block.$el.find('.invoice__footer');

                block.invoiceProductsCollection = new invoiceProductsCollection({
                    invoiceId: block.invoiceId
                });

                block.invoiceModel = new invoiceModel({
                    id: block.invoiceId
                });

                block.invoiceModel.fetch();
                block.invoiceProductsCollection.fetch();

                block.listenTo(block.invoiceModel, 'sync', function(){
                    block.$head.html(block.tpl.head({
                        block: block
                    }));
                    block.$footer.html(block.tpl.footer({
                        block: block
                    }));
                });

                block.listenTo(block.invoiceProductsCollection, 'sync', function(){
                    block.$table.html(block.tpl.table({
                        block: block
                    }));
                });
            },
            utils: utils,
            tpl: {
                head: headTpl,
                table: tableTpl,
                footer: footerTpl,
                row: rowTpl
            }
        });
    }
);