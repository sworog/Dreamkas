require(
    {
        baseUrl: '/',
        paths: {
            'backbone.queryparams': 'libs/backbone/backbone.queryparams'
        },
        shim: {
            'backbone.queryparams': ['backbone']
        }
    },
    [
        'jquery',
        'backbone',
        'underscore',
        'helpers/helpers',
        'routers/mainRouter'
    ],
    function($, Backbone, _, helpers, router) {
        window.LH = _.extend({
            helpers: helpers
        }, window.LH);

        $(function() {
            Backbone.history.start({
                pushState: true
            });

            $('body').on('click', '[href]', function(e) {
                e.preventDefault();
                router.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });