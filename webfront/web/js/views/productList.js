var ProductItem = Backbone.View.extend({
    tagName: "a",
    attributes: {
        lh_table_row: "true",
        name: "product"
    },

    initialize: function() {
    },

    render: function() {
        var data = this.model.toJSON();

        data.purchasePrice = Helpers.pricesFloatToView(data.purchasePrice);

        this.$el.html(this.template(data));
        this.$el.attr('href', "/product/view/" + this.model.id);

        return this;
    }
});

var ProductsList = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#productsList").html()),
    templateRow: Mustache.compile($("#productItem").html()),

    initialize: function() {
//        _.bindAll(this, 'addOne', 'addAll', 'render');

        this.collection.bind('add', this.addOne, this);
//        this.collection.bind('sync', this.addAll);
//        this.collection.bind('all', this.render, this);

        this.collection.fetch();
    },

    render: function() {
        this.$el.html(this.template());

        this.addAll();

        return this;
    },

    addOne: function(product) {
        var view = new ProductItem({model: product});
        view.template = this.templateRow;
        this.$el.find("[lh_table]").append(view.render().el);
    },

    addAll: function() {
        this.collection.forEach(this.addOne);
    }
})