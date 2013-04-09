var InvoiceAddProductListRowTable = Backbone.View.extend({
    template: Mustache.compile($("#invoiceProductListRowTable").html()),
    attributes: {
        lh_table_row: "true",
        name: "product"
    },

    initialize: function() {
        this.product = new Product({id: this.model.get('product')});
        this.product.bind('sync', this.render, this);
        this.product.fetch();
    },

    render: function() {
        var data = this.model.toJSON();
        data.productModel = this.product.toJSON();

        this.$el.html(this.template(data));

        this.$el.find("[name='productBarcode']").each(function(item) {
            $(this).barcode($(this).text().trim(), 'code128', {
                barWidth: 1,
                barHeight: 30
            });
        });

        return this;
    }
})