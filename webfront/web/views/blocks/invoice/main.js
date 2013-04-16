define(
    [
        '/models/invoice.js',
        '/collections/invoiceProducts.js',
        '/utils/main.js',
        './templates.js'
    ],
    function(invoiceModel, invoiceProductsCollection, utils, templates) {
        return Backbone.Block.extend({
            initialize: function() {
                var block = this;

                block.render();

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
                    block.renderTable();
                });
            },
            renderTable: function() {
                var block = this;

                block.$table.html(block.tpl.table({
                    block: block
                }));

                block.$table.find("[name='productBarcode']").each(function(item) {
                    $(this).barcode($(this).text().trim(), 'code128', {
                        barWidth: 1,
                        barHeight: 25
                    });
                });
            },
            utils: utils,
            tpl: templates
        });
    }
);