define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        blocks: {
            form_login: function(){
                var Block = require('blocks/form/form_login/form_login');

                return new Block();
            }
        }
    });
});