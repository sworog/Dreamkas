var InvoiceAddProductListRowTable = Backbone.View.extend({
    template: Mustache.compile($("#invoiceProductListRowTable").html()),
    attributes: {
        lh_table_row: "true",
        name: "invoiceProduct"
    },

    initialize: function() {
        if(this.model.get('productModel') instanceof Product){
            this.product = this.model.get('productModel');
            this.render();
        } else {
            this.product = new Product({id: this.model.get('product')});
            this.product.bind('sync', this.render, this);
            this.product.fetch();
        }
    },

    render: function() {
        var data = this.model.toJSON();
        data.productModel = this.product.toJSON();

        if(data.productModel.units) {
            for(var unitId in this.product.unitsEnum) {
                if(unitId == data.productModel.units) {
                    data.productModel.units = this.product.unitsEnum[unitId];
                    data.productModel.units.value = unitId;
                }
            }
        }
        data.price = Helpers.pricesFloatToView(data.price);
        data.totalPrice = Helpers.pricesFloatToView(data.totalPrice);

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