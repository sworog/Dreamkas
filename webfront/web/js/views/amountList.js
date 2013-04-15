var AmountItem = Backbone.View.extend({
    tagName: "a",
    attributes: {
        lh_table_row: "true",
        name: "amountItem"
    },

    template: Mustache.compile($("#amountItem").html()),

    initialize: function() {
    },

    render: function() {
        var data = this.model.toJSON();

        data.purchasePrice = Helpers.pricesFloatToView(data.purchasePrice);

        if(data.units) {
            for(var unitId in this.model.unitsEnum) {
                if(unitId == data.units) {
                    data.units = this.model.unitsEnum[unitId];
                    data.units.value = unitId;
                }
            }
        }

        this.$el.html(this.template(data));

        return this;
    }
});

var AmountsList = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#amountsList").html()),

    initialize: function() {
        _.bindAll(this, 'addOne', 'addAll', 'render');

//        this.collection.bind('add', this.addOne);
//        this.collection.bind('sync', this.addAll);
        this.collection.bind('all', this.render, this);

        this.collection.fetch();
    },

    render: function() {
        this.$el.html(this.template());

        this.addAll();

        return this;
    },

    addOne: function(product) {
        var view = new AmountItem({model: product});
        this.$el.find("[lh_table]").append(view.render().el);
    },

    addAll: function() {
        this.collection.forEach(this.addOne);
    }
});
