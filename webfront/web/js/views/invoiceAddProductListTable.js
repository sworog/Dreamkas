var InvoiceAddProductListTable = Backbone.View.extend({
    template: Mustache.compile($("#invoiceProductListTable").html()),
    templateInfo: Mustache.compile($("#invoiceAddProductListInfo").html()),
    emptyList: true,
    isRendered: false,

    initialize: function() {
        this.collection.bind('add', this.addLast, this);
        this.collection.bind('all', this.render,this);
        this.model.bind('change', this.renderInfo, this);
    },

    render: function() {
        if( ! this.isRendered) {
            this.$el.html(this.template());
            this.isRendered = true;
        }

        if(this.collection.length > 0) {
            this.$el.show();
            this.renderInfo();
        } else {
            this.$el.hide();
        }

        return this;
    },

    addLast: function(addedModel) {
        var view = new InvoiceAddProductListRowTable({model: addedModel});
        this.$el.find("[lh_table]").append(view.el);

        this.model.set(addedModel.get('invoiceModel').toJSON());
    },

    renderInfo: function() {
        var data = this.model.toJSON();
        data.sumTotal = Helpers.pricesFloatToView(data.sumTotal);
        this.$el.find(".invoiceAddProductListInfo[lh_card_sub_title]")
            .html(this.templateInfo(data));
    }
});