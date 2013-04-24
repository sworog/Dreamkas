define(
    [
        '/views/kit/form/form.js',
        '/models/product.js',
        '/utils/main.js',
        '/routers/main.js',
        'tpl!./main.html'
    ],
    function(Form, productModel, utils, router, mainTpl) {
        return Form.extend({
            defaults: {
                defaultInputLinkText: 'Введите значение',
                productId: null
            },
            tpl: {
                main: mainTpl
            },

            initialize: function() {
                var block = this;

                this.productModel = new productModel({
                    id: this.productId
                });

                if (this.productId){
                    this.productModel.fetch();
                } else {
                    this.render();
                }

                this.listenTo(this.productModel, {
                    sync: function(){
                        block.render();
                    }
                });
            },
            render: function(){
                Form.prototype.render.apply(this, arguments);

                this.$retailPricePreferenceInput = this.$el.find('[name="retailPricePreference"]');
                this.$retailPriceInput = this.$el.find('[name="retailPrice"]');
                this.$retailMarkupInput = this.$el.find('[name="retailMarkup"]');
                this.$purchasePriceInput = this.$el.find('[name="purchasePrice"]');

                this.$retailPriceLink = this.$retailPriceInput.next('.productForm__inputLink');
                this.$retailMarkupLink = this.$retailMarkupInput.next('.productForm__inputLink');

                this.renderRetailMarkupLink();
                this.renderRetailPriceLink();
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
                },
                'submit': function(e){
                    e.preventDefault();
                    var block = this,
                        formData = Backbone.Syphon.serialize(e.target);

                    this.productModel.save(formData, {
                        success: function(){
                            router.navigate('/product/list', {
                                trigger: true
                            });
                        },
                        error: function(model, response){
                            block.showErrors(JSON.parse(response.responseText));
                        }
                    });
                }
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
                var purchasePrice = utils.normalizePrice(this.$purchasePriceInput.val()),
                    retailMarkup = utils.normalizePrice(this.$retailMarkupInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailMarkup || _.isNaN(purchasePrice) || _.isNaN(retailMarkup)) {
                    calculatedVal = '';
                } else {
                    calculatedVal = utils.formatPrice(purchasePrice + retailMarkup / 100 * purchasePrice);
                }

                this.$retailPriceInput
                    .val(calculatedVal)
                    .change();
            },
            calculateRetailMarkup: function() {
                var retailPrice = utils.normalizePrice(this.$retailPriceInput.val()),
                    purchasePrice = utils.normalizePrice(this.$purchasePriceInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailPrice || _.isNaN(purchasePrice) || _.isNaN(retailPrice)){
                    calculatedVal = '';
                } else {
                    calculatedVal = utils.formatPrice(retailPrice * 100 / purchasePrice - 100);
                }

                this.$retailMarkupInput
                    .val(calculatedVal)
                    .change();
            },
            renderRetailPriceLink: function() {
                this.$retailPriceLink.find('.productForm__inputLinkText').html($.trim(this.$retailPriceInput.val()) || this.defaultInputLinkText);
            },
            renderRetailMarkupLink: function() {
                this.$retailMarkupLink.find('.productForm__inputLinkText').html($.trim(this.$retailMarkupInput.val()) || this.defaultInputLinkText);
            }
        });
    }
);