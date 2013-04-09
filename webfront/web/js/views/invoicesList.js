var InvoicesListItemView = Backbone.View.extend({
    tagName: "a",
    attributes: {
        lh_table_row: "true",
        name: "invoice"
    },

    template: Mustache.compile($("#invoiceItem").html()),

    initialize: function() {
    },

    render: function() {
        var data = this.model.toJSON();

        data.acceptanceDate = Helpers.dateTimeFormat(data.acceptanceDate);

        this.$el.html(this.template(data));
        this.$el.attr('href', "/invoice/view/" + this.model.id);

        return this;
    }
});

var InvoicesListView = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#invoicesList").html()),

    initialize: function() {
        _.bindAll(this, 'addOne', 'addAll', 'render');

//        this.collection.bind('add', this.addOne);
//        this.collection.bind('sync', this.addAll);
        this.collection.bind('all', this.render);

        this.collection.fetch();
    },

    render: function() {
        this.$el.html(this.template());

        this.addAll();

        return this;
    },

    addOne: function(model) {
        var view = new InvoicesListItemView({model: model});
        this.$el.find("[lh_table]").append(view.render().el);
    },

    addAll: function() {
        this.collection.forEach(this.addOne);
    }
})