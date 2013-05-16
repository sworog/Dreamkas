require(
    {
        baseUrl: '/',
        paths: {
            tpl: '/libs/require/tpl'
        }
    },
    [
        '/helpers/helpers.js',
        '/routers/mainRouter.js'
    ],
    function(helpers, router) {
        moment.lang('ru');

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