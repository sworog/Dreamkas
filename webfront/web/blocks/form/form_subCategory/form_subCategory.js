define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        SubCategoryModel = require('models/subCategory');

    return Form.extend({
        el: '.form_subCategory',
        model: null,
        collection: null,
        groupId: null,
        categoryId: null,
        elements: {
            nameInput: '[name="name"]'
        },
        listeners: {
            'submit:success': function() {
                var block = this;

                if (block.collection){
                    block.model = new SubCategoryModel({
                        groupId: block.groupId,
                        categoryId: block.categoryId
                    });
                    block.elements.nameInput.value = '';
                    block.elements.nameInput.focus();
                }
            },
            'submit:start': function() {
                var block = this;

                block.elements.nameInput.classList.add('preloader_stripes');
            },
            'submit:complete': function() {
                var block = this;

                block.elements.nameInput.classList.remove('preloader_stripes');
            }
        },
        initialize: function(){
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.model = new SubCategoryModel({
                groupId: block.groupId,
                categoryId: block.categoryId
            });

            block.elements.nameInput.addEventListener('blur', function(){
                block.elements.nameInput.value = '';
                block.removeErrors();
            });
        }
    });
});