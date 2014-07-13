define(function(require) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        __name__: 'form_writeOff',
        template: require('ejs!blocks/form/form_writeOff/templates/index.html'),
        redirectUrl: function(){
            var block = this;

            return '/writeOffs/' + block.model.id + '?editMode=true';
        }
    });
});