define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        GroupModel = require('models/group/group');

    return Form.extend({
        el: '.form_group',
        model: function(){
            return new GroupModel();
        },
        collection: null,
        initialize: function(){

            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.on('submit:success', function(){
                block.$el.closest('.modal').modal('hide');
            });

        }
    });
});