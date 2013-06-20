define(function(require) {
    //requirements
    var Form = require('kit/form/form');

    require('blocks/select/select_userRole');

    return Form.extend({
        blockName: 'form_user',
        redirectUrl: '/users',
        templates: {
            index: require('tpl!./templates/form_user.html')
        },
        initialize: function(){
            var block = this;

            if (block.model.id){
                block.redirectUrl = '/users/' + block.model.id
            }

            Form.prototype.initialize.call(this);
        }
    });
});