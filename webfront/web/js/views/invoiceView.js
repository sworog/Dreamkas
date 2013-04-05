var InvoiceView = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#invoiceView").html()),

    initialize: function() {
        _.bindAll(this, 'render');

        this.model.bind('all', this.render);
    },

    render: function() {
        var data = this.model.toJSON();

        var date = data.acceptanceDate.replace(/(\d+)\-(\d+)\-(\d+)T(\d+):(\d+).*/, "$3.$2.$1 $4:$5");
        data.acceptanceDate = date;

        if(data.supplierInvoiceDate) {
            data.supplierInvoiceDate = data.supplierInvoiceDate.replace(/(\d+)\-(\d+)\-(\d+)T(\d+):(\d+).*/, "$3.$2.$1");
        }

        this.$el.html(this.template(data));

        return this;
    }
});