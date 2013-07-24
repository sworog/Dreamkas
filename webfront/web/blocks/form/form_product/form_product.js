define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            __name__: 'form_product',
            defaultInputLinkText: 'Введите значение',
            model: null,
            subCategoryModel: null,
            templates: {
                index: require('tpl!blocks/form/form_product/templates/index.html')
            },
            events: {
                'click .productForm__inputLink': 'click .productForm__inputLink',
                'keyup [name="purchasePrice"]': 'keyup [name="purchasePrice"]',
                'keyup [name="retailMarkup"]': 'keyup [name="retailMarkup"]',
                'keyup [name="retailPrice"]': 'keyup [name="retailPrice"]',
                'change [name="retailMarkup"]': 'change [name="retailMarkup"]',
                'change [name="retailPrice"]': 'change [name="retailPrice"]'
            },
            'click .productForm__inputLink': function(e) {
                e.preventDefault;
                var $link = $(e.currentTarget),
                    $linkedInput = $link.prev('.productForm__linkedInput');

                switch ($linkedInput.attr('name')) {
                    case 'retailMarkup':
                        this.showRetailMarkupInput();
                        break;
                    case 'retailPrice':
                        this.showRetailPriceInput();
                        break;
                }
            },
            'keyup [name="purchasePrice"]': function(e) {
                if (this.$retailPriceInput.is(':hidden')) {
                    this.calculateRetailPrice();
                }

                if (this.$retailMarkupInput.is(':hidden')) {
                    this.calculateRetailMarkup();
                }
            },
            'keyup [name="retailMarkup"]': function() {
                this.calculateRetailPrice();
            },
            'keyup [name="retailPrice"]': function() {
                this.calculateRetailMarkup();
            },
            'change [name="retailMarkup"]': function() {
                this.renderRetailMarkupLink();
            },
            'change [name="retailPrice"]': function() {
                this.renderRetailPriceLink();
            },
            initialize: function(){
                var block = this;

                Form.prototype.initialize.apply(block, arguments);

                if (block.model.id){
                    block.redirectUrl = '/products/' + block.model.id
                } else {
                    block.redirectUrl = '/catalog/' + block.model.get('group').id + '/' + block.model.get('category').id + '/' + block.model.get('subCategory').id
                }
            },
            findElements: function(){
                var block = this;
                Form.prototype.findElements.apply(block, arguments);

                block.$retailPricePreferenceInput = block.$el.find('[name="retailPricePreference"]');
                block.$retailPriceInput = block.$el.find('[name="retailPrice"]');
                block.$retailMarkupInput = block.$el.find('[name="retailMarkup"]');
                block.$purchasePriceInput = block.$el.find('[name="purchasePrice"]');

                block.$retailPriceLink = block.$retailPriceInput.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupInput.next('.productForm__inputLink');
            },
            render: function(){
                var block = this;

                Form.prototype.render.call(this);

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
            },
            showRetailMarkupInput: function() {
                this.$retailPriceInput.addClass('productForm__hiddenInput');
                this.$retailMarkupInput
                    .removeClass('productForm__hiddenInput')
                    .focus();

                this.$retailPricePreferenceInput.val('retailMarkup');
            },
            showRetailPriceInput: function() {
                this.$retailMarkupInput.addClass('productForm__hiddenInput');
                this.$retailPriceInput
                    .removeClass('productForm__hiddenInput')
                    .focus();

                this.$retailPricePreferenceInput.val('retailPrice');
            },
            calculateRetailPrice: function() {
                var purchasePrice = LH.normalizePrice(this.$purchasePriceInput.val()),
                    retailMarkup = LH.normalizePrice(this.$retailMarkupInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailMarkup || _.isNaN(purchasePrice) || _.isNaN(retailMarkup)) {
                    calculatedVal = '';
                } else {
                    calculatedVal = LH.formatPrice(+(retailMarkup / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                this.$retailPriceInput
                    .val(calculatedVal)
                    .change();
            },
            calculateRetailMarkup: function() {
                var retailPrice = LH.normalizePrice(this.$retailPriceInput.val()),
                    purchasePrice = LH.normalizePrice(this.$purchasePriceInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailPrice || _.isNaN(purchasePrice) || _.isNaN(retailPrice)){
                    calculatedVal = '';
                } else {
                    calculatedVal = LH.formatPrice(+(retailPrice * 100 / purchasePrice).toFixed(2) - 100);
                }

                this.$retailMarkupInput
                    .val(calculatedVal)
                    .change();
            },
            renderRetailPriceLink: function() {
                var price = $.trim(this.$retailPriceInput.val()),
                    text;

                if (price){
                    text = LH.formatPrice(price) + ' руб.'
                } else {
                    text = this.defaultInputLinkText;
                }

                this.$retailPriceLink
                    .find('.productForm__inputLinkText')
                    .html(text);
            },
            renderRetailMarkupLink: function() {
                var markup = $.trim(this.$retailMarkupInput.val()),
                    text;

                if (markup){
                    text = LH.formatPrice(markup) + '%'
                } else {
                    text = this.defaultInputLinkText;
                }

                this.$retailMarkupLink
                    .find('.productForm__inputLinkText')
                    .html(text);
            }
        });
    }
);