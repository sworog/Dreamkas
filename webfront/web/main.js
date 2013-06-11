require(
    {
        baseUrl: '/',
        paths: {
            tpl: '/libs/require/tpl'
        },
        packages: [
            {
                name: 'moment',
                location: '/libs/moment',
                main: 'moment.min'
            }
        ]
    },
    [
        '/helpers/helpers.js',
        '/routers/mainRouter.js'
    ],
    function(helpers, router) {
        window.LH = {
            helpers: helpers
        };

        $(function(){
            Backbone.history.start({
                pushState: true
            });

            $('body').on('click', '[href]', function(e) {
                e.preventDefault();
                router.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });