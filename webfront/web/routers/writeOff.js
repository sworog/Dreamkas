define(function(require) {
        //requirements
        var page = require('blocks/page/page'),
            BaseRouter = require('routers/baseRouter');

        var Router = BaseRouter.extend({
            routes: {
                'writeOffs': 'list',
                'writeOff/create': 'create',
                'writeOff/:writeOffId': 'view'
            },
            view: function(writeOffId, params) {
                page.open('/pages/writeOff/view.html', {
                    writeOffId: writeOffId,
                    params: params || {}
                });
            },
            list: function(){
                page.open('/pages/writeOff/list.html');
            },
            create: function(){
                page.open('/pages/writeOff/create.html');
            }
        });

        return new Router();
    }
);