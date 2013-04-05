var InvoiceAddProductView = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    isRendered: false,

    template: Mustache.compile($("#invoiceAddProduct").html()),
    templateDataView: Mustache.compile($("#invoiceAddProductInfo").html()),

    events: {},

    initialize: function() {
        this.model.bind('sync', this.render, this);

        if(this.collection == undefined){
            this.collection = new InvoiceProductsCollection;
        }
    },

    render: function() {
        if( ! this.isRendered) {
            this.$el.html(this.template());
        }

        var data = this.model.toJSON();

        this.$el.find("[name='invoice']").html(this.templateDataView(data));

        var listView = new InvoiceAddProductListTable({collection: this.collection});
        this.$el.find("[name='invoiceProductsList']").html(listView.render().el);

        var formView = new Invoice

//        this.$el.find("[name='productBarcode']").each(function(item) {
//            $(this).barcode($(this).text().trim(), 'code128', {
//                barWidth: 1,
//                barHeight: 30
//            });
//        });

        return this;
    }
});