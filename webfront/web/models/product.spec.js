define(function(require, exports, module) {
    var Product = require('./product'),
        SubCategory = require('./catalogSubCategory');
    window.LH.units = require('utils/units');
    window.LH.formatMoney = require('utils/formatMoney');
    window.LH.formatAmount = require('utils/formatAmount');
    window.LH.formatDate = require('utils/formatDate');
    window.LH.translate = function(text){
        var dictionary = require('dictionary'),
            translate = require('kit/utils/translate');
        return translate(dictionary, text);
    };

    describe("Test product type Weight", function() {
        var product, productJSON;

        productJSON = require('fixtures/productTypeWeight');

        beforeEach(function() {
            spyOn($, 'ajax').andCallFake(function(options) {
                options.success(productJSON);
            });
            product = new Product();
            product.fetch();
        });

        afterEach(function() {
            product = undefined;
        });

        it("saveData method for product type Weight", function() {
            var expectedSaveDataTypeSpecific = {
                nameOnScales: 'Помидор (Испания)',
                descriptionOnScales: 'Хороший поидор (Испания)',
                ingredients: 'Помидор, пестициды в ассортименте',
                shelfLife: 48
            };

            var actualJSON = product.saveData();
            expect(actualJSON.typeProperties).toEqual(expectedSaveDataTypeSpecific);
            expect(actualJSON.type).toEqual("weight");

            var form_product = require('blocks/form/form_product/form_product'),
                fake_el = $("<div></div>");
            var form = new form_product({
                model: product,
                el: fake_el[0]
            });

            var expectedFormData = expectedSaveDataTypeSpecific;
            expectedFormData.shelfLife = '48';

            var actualData = form.getData();
            expect(actualData.type).toEqual('weight');
            expect(actualData.typeProperties).toEqual(expectedFormData);
        });
    });

    describe("Test product type Unit", function() {
        var product, productJSON;

        productJSON = require('fixtures/productTypeUnit');

        beforeEach(function() {
            spyOn($, 'ajax').andCallFake(function(options) {
                options.success(productJSON);
            });
            product = new Product();
            product.fetch();
        });

        afterEach(function() {
            product = undefined;
        });

        it("saveData method for product type Unit", function() {
            var expectedSaveDataTypeSpecific = {};

            product.fetch();
            var actualJSON = product.saveData();
            expect(actualJSON.typeProperties).toEqual(expectedSaveDataTypeSpecific);
            expect(actualJSON.type).toEqual("unit");

            var form_product = require('blocks/form/form_product/form_product'),
                fake_el = $("<div></div>");
            var form = new form_product({
                model: product,
                el: fake_el[0]
            });

            var actualData = form.getData();
            expect(actualData.type).toEqual('unit');

            expect(actualData.typeProperties).toBeUndefined();
        });
    });

    describe("Test product create form", function() {
        var product = new Product({
            subCategory: require('fixtures/subCategory')
        }, {
            parse: true
        });
        var fake_subCategoryModel = new SubCategory(product.get('subCategory'));

        it("test product form get data", function() {
            var form_product = require('blocks/form/form_product/form_product'),
                fake_el = $("<div></div>");
            var form = new form_product({
                model: product,
                el: fake_el[0],
                subCategoryModel: fake_subCategoryModel
            });

            var actualData = form.getData();
            expect(actualData.type).toEqual('unit');
            expect(actualData.typeProperties).toBeUndefined();

            form.$productTypeRadio.find('input[value=weight]').click();
            actualData = form.getData();
            expect(actualData.type).toEqual('weight');
            expect(actualData.typeProperties).toBeUndefined();
            expect(form.$productTypePropertiesFields.find('input[name="typeProperties.shelfLife"]')).not.toBeNull()
        });
    })
});