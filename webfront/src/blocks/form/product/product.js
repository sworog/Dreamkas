define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        modalId: null,
        selectedGroupId: function(){
            return PAGE.models.group && PAGE.models.group.id;
        },
        collection: function(){
            return PAGE.collections.groupProducts;
        },
        collections: {
            groups: function(){
                return PAGE.collections.groups;
            }
        },
        events: {
            'keyup input[name="purchasePrice"]': function(e){
                var block = this;

                block.calculateMarkup();
            },
            'keyup input[name="sellingPrice"]': function(e){
                var block = this;

                block.calculateMarkup();
            },
            'click .form_product__removeLink': function(e) {
                var block = this;

                e.target.classList.add('loading');

                block.model.destroy().then(function() {
                    e.target.classList.remove('loading');
                });
            }
        },
        blocks: {
            select_group: require('blocks/select/group/group'),
            select_vat: require('blocks/select/vat/vat')
        },
        render: function(){

            Form.prototype.render.apply(this, arguments);

            this.calculateMarkup();
        },
        showFieldError: function(data, field) {
            var block = this;

            if (field === 'subCategory'){

                data.errors = data.errors || [];

                _.forEach(data.children, function(value, key){
                    if (value.errors){
                        data.errors = data.errors.concat(value.errors);
                    }
                });
            }

            Form.prototype.showFieldError.call(block, data, field);
        },
        calculateMarkup: function(){
            var block = this,
                purchasePrice = block.normalizeNumber(block.$('[name="purchasePrice"]').val()),
                sellingPrice = block.normalizeNumber(block.$('[name="sellingPrice"]').val()),
                markup = 100 * (sellingPrice - purchasePrice) / purchasePrice,
                $product__markup = block.$('.form_product__markup'),
                $product__markupText = block.$('.form_product__markupText');

            if (_.isNaN(markup)) {
                $product__markup.hide();
            } else {
                $product__markupText.html(block.formatNumber(markup.toFixed(1)));
                $product__markup.show();
            }
        }
    });
});