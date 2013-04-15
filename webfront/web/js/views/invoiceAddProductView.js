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
            this.model.unbind('sync', this.render, this);
        }

        var data = this.model.toJSON();
        data.acceptanceDate = Helpers.dateTimeFormat(data.acceptanceDate);
        if(data.supplierInvoiceDate) {
            data.supplierInvoiceDate = Helpers.dateFormat(data.supplierInvoiceDate);
        }

        this.$el.find("[name='invoice']").html(this.templateDataView(data));

        var listView = new InvoiceAddProductListTable({
            collection: this.collection,
            model: this.model
        });
        this.$el.find("[name='invoiceProductsList']").html(listView.render().el);

        var formView = new InvoiceAddProductFormView({
            model: new InvoiceProduct({
                invoice: this.model.get('id')
            }),
            collection: this.collection
        });
        formView.invoice = this.model;
        this.$el.find("[name='invoiceAddProductsForm']").html(formView.render().el);

        return this;
    }
});