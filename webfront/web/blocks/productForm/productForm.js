define(
    [
        '/kit/form/form.js',
        '/models/product.js',
        '/helpers/helpers.js',
        '/routers/mainRouter.js',
        './tpl/tpl.js'
    ],
    function(Form, ProductModel, helpers, router, tpl) {
        return Form.extend({
            defaultInputLinkText: 'Введите значение',
            productId: null,
            tpl: tpl,

            initialize: function() {
                var block = this;

                block.productModel = new ProductModel({
                    id: block.productId
                });

                if (block.productId){
                    block.productModel.fetch();
                } else {
                    block.render();
                }

                block.listenTo(block.productModel, {
                    sync: function(){
                        block.render();
                    }
                });
            },
            events: {
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
                }
            },
            render: function(){
                var block = this;
                Form.prototype.render.apply(block, arguments);

                block.$retailPricePreferenceInput = block.$el.find('[name="retailPricePreference"]');
                block.$retailPriceInput = block.$el.find('[name="retailPrice"]');
                block.$retailMarkupInput = block.$el.find('[name="retailMarkup"]');
                block.$purchasePriceInput = block.$el.find('[name="purchasePrice"]');

                block.$retailPriceLink = block.$retailPriceInput.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupInput.next('.productForm__inputLink');

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
            },
            submit: function(){
                var block = this,
                    deferred = $.Deferred(),
                    formData = Backbone.Syphon.serialize(block);

                block.productModel.save(formData, {
                    success: function(){
                        router.navigate(block.productId ? '/product/' + block.productId : '/products', {
                            trigger: true
                        });
                    },
                    error: function(model, response){
                        deferred.reject(JSON.parse(response.responseText));
                    }
                });

                return deferred.promise();
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
                var purchasePrice = helpers.normalizePrice(this.$purchasePriceInput.val()),
                    retailMarkup = helpers.normalizePrice(this.$retailMarkupInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailMarkup || _.isNaN(purchasePrice) || _.isNaN(retailMarkup)) {
                    calculatedVal = '';
                } else {
                    calculatedVal = helpers.formatPrice(+(retailMarkup / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                this.$retailPriceInput
                    .val(calculatedVal)
                    .change();
            },
            calculateRetailMarkup: function() {
                var retailPrice = helpers.normalizePrice(this.$retailPriceInput.val()),
                    purchasePrice = helpers.normalizePrice(this.$purchasePriceInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailPrice || _.isNaN(purchasePrice) || _.isNaN(retailPrice)){
                    calculatedVal = '';
                } else {
                    calculatedVal = helpers.formatPrice(+(retailPrice * 100 / purchasePrice).toFixed(2) - 100);
                }

                this.$retailMarkupInput
                    .val(calculatedVal)
                    .change();
            },
            renderRetailPriceLink: function() {
                var price = $.trim(this.$retailPriceInput.val()),
                    text;

                if (price){
                    text = helpers.formatPrice(price) + ' руб.'
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
                    text = helpers.formatPrice(markup) + '%'
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