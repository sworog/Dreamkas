var InvoiceAddProductListTable = Backbone.View.extend({
    template: Mustache.compile($("#invoiceProductListTable").html()),
    templateInfo: Mustache.compile($("#invoiceAddProductListInfo").html()),
    emptyList: true,
    isRendered: false,

    initialize: function() {
        this.collection.bind('add', this.addLast, this);
        this.collection.bind('all', this.render,this);
        this.model.bind('sync', this.renderInfo, this);
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

    addLast: function() {
        var lastModel = this.collection.last();
        var view = new InvoiceAddProductListRowTable({model: lastModel});
        this.$el.find("[lh_table]").append(view.el);
    },

    renderInfo: function() {
        var data = this.model.toJSON();
        this.$el.find(".invoiceAddProductListInfo[lh_card_sub_title]")
            .html(this.templateInfo(data));
    }
});