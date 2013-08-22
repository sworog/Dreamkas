define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form');

    require('blocks/select/select_userRole/select_userRole');

    return Form.extend({
        __name__: 'form_user',
        redirectUrl: '/users',
        templates: {
            index: require('tpl!blocks/form/form_user/templates/index.html')
        },
        initialize: function(){
            var block = this;

            if (block.model.id){
                block.redirectUrl = '/users/' + block.model.id
            }
        }
    });
});