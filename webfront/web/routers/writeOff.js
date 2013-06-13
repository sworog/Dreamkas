define(function(require) {
        //requirements
        var content_main = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        var Router = BaseRouter.extend({
            routes: {
                'writeOffs': 'list',
                'writeOff/create': 'create',
                'writeOff/:writeOffId': 'view'
            },
            view: function(writeOffId, params) {
                content_main.load('/pages/writeOff/view.html', {
                    writeOffId: writeOffId,
                    params: params || {}
                });
            },
            list: function(){
                content_main.load('/pages/writeOff/list.html');
            },
            create: function(){
                content_main.load('/pages/writeOff/create.html');
            }
        });

        return new Router();
    }
);