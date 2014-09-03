define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            group: function(){
                var GroupModel = require('models/group/group');

                return PAGE.get('models.group') || new GroupModel;
            }
        },
        blocks: {
            form_group: require('blocks/form/group/group')
        }
    });
});