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

        var date = new Date(data.acceptanceDate);
        data.acceptanceDate = date.format(this.model.datePrintFormat + " " + this.model.timeFormat);

        this.$el.html(this.template(data));

        return this;
    }
});