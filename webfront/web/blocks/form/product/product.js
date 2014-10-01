define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        selectedGroupId: function(){
            return PAGE.models.group.id;
        },
        model: function(){
            return PAGE.models.product;
        },
        collection: function(){
            return PAGE.collections.groupProducts;
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
            'click .confirmLink__confirmation .product__removeLink': function(e) {
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
        submit: function() {
            var block = this;

            if (block.data.newGroupName.length){

                block.data.subCategory = {
                    name: block.data.newGroupName
                };
            }

            return Form.prototype.submit.apply(block, arguments);
        },
        submitSuccess: function(){

            var groupId = this.model.get('subCategory.id');

            if (groupId !== PAGE.params.groupId){
                this.redirectUrl = '/catalog/groups/' + groupId;
            }

            Form.prototype.submitSuccess.apply(this, arguments);

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