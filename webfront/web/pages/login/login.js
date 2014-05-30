define(function(require, exports, module) {
    //requirements
    var Page = require('pages/page'),
        SignupModel = require('models/signup');

    return Page.extend({
        data: {
            login: {
                username: null,
                password: null
            }
        },
        init: function(){
            this._super();

            if (this.get('params.signup')==='success'){
                this.set('login.username', SignupModel.email);
            }
        },
        partials: {
            content: require('rv!./content.html'),
            globalNavigation: require('rv!./globalNavigation.html')
        },
        components: {
            form_login: require('blocks/form/form_login/form_login')
        }
    });
});