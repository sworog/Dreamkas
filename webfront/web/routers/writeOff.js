define(
    [
        '/kit/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {
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