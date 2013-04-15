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

        var date = Helpers.dateTimeFormat(data.acceptanceDate);
        data.acceptanceDate = date;

        if(data.supplierInvoiceDate) {
            data.supplierInvoiceDate = Helpers.dateFormat(data.supplierInvoiceDate);
        }

        this.$el.html(this.template(data));

        return this;
    }
});