define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        blocks: {
            form_signup: function(){
                var Block = require('blocks/form/form_signup/form_signup');

                return new Block();
            }
        }
    });
});