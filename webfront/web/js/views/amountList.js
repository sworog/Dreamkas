var AmountsList = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#amountsList").html()),
    templateRow: Mustache.compile($("#amountItem").html()),

    initialize: function() {
        this.collection.bind('add', this.addOne, this);

        this.render();

        this.collection.fetch();
    },

    render: function() {
        this.$el.html(this.template());

        return this;
    },

    addOne: function(product) {

        var data = product.toJSON();
        data.purchasePrice = Helpers.pricesFloatToView(data.purchasePrice);
        if(data.lastPurchasePrice)
            data.lastPurchasePrice = Helpers.pricesFloatToView(data.lastPurchasePrice);
        data.units = product.unitsEnum[data.units].textViewShort;

        this.$el.find("[lh_table]").append(this.templateRow(data));
    },

    addAll: function() {
        this.collection.forEach(this.addOne);
    }
});
