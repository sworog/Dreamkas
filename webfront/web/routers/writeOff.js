define(
    [
        '/pages/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {
        return BaseRouter.extend({
            routes: {
                'writeOff': 'list',
                'writeOff/:writeOffId': 'view',
                'writeOff/create': 'create'
            },
            view: function(writeOffId, params) {
                page.open('/pages/writeOff/view.html', {
                    writeOffId: writeOffId,
                    params: params
                });
            },
            list: function(){
                page.open('/pages/writeOff/list.html');
            },
            create: function(){
                page.open('/pages/writeOff/create.html');
            }
        });
    }
);