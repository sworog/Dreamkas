var InvoiceAddProductListRowTable = Backbone.View.extend({
    template: Mustache.compile($("#invoiceProductListRowTable").html()),

    initialize: function() {
        this.product = new Product({id: this.model.get('product')});
        this.product.bind('sync', this.render, this);
        this.product.fetch();
    },

    render: function() {
        var data = this.model.toJSON();
        data.productModel = this.product.toJSON();

        this.$el.html(this.template(data));

        return this;
    }
})