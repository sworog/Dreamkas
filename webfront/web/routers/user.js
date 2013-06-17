define(function(require) {
    //requirements
    var BaseRouter = require('routers/baseRouter'),
        content = require('blocks/content/content_main');

    var Router = BaseRouter.extend({
        routes: {
            'users': 'list',
            'user/list': 'list',
            'user/edit/:userId': require('pages/user/form'),
            'user/create': require('pages/user/form'),
            'user/:userId': 'view'
        },
        list: function(){
            content.load('/pages/user/list.html');
        },
        view: function(userId){
            content.load('/pages/user/view.html', {
                userId: userId
            });
        }
    });

    return new Router();
});