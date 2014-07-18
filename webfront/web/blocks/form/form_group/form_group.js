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
        events: {
            'click .confirmLink__confirmation .form__groupRemoveLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.model.destroy().then(function(){
                    block.$el.closest('.modal').modal('hide');
                });
            }
        },
        initialize: function(){

            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.on('submit:success', function(){

                block.$el.closest('.modal').modal('hide');

                if (!block.__model.id){
                    block.model = new GroupModel();
                }
            });

        }
    });
});