define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        GroupModel = require('models/group');

    return Form.extend({
        el: '.form_group',
        model: null,
        collection: null,
        elements: {
            nameInput: '[name="name"]'
        },
        listeners: {
            'submit:success': function() {
                var block = this;

                if (block.collection){
                    block.model = new GroupModel();
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

            block.model = new GroupModel();

            block.elements.nameInput.addEventListener('blur', function(){
                block.elements.nameInput.value = '';
                block.removeErrors();
            });
        }
    });
});