var ProductView = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#productView").html()),

    initialize: function() {
        _.bindAll(this, 'render');

        this.model.bind('sync', this.render, this);
    },

    render: function() {
        var data = this.model.toJSON();

        // Преобразовываем вывод едениц измерения
        if(data.units) {
            for(var unitId in this.model.unitsEnum) {
                if(unitId == data.units) {
                    data.units = this.model.unitsEnum[unitId];
                    data.units.value = unitId;
                }
            }
        }
        data.purchasePrice = Helpers.pricesFloatToView(data.purchasePrice);

        this.$el.html(this.template(data));

        return this;
    }
});