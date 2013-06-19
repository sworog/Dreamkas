require(
    {
        baseUrl: '/',
        paths: {
            'backbone.queryparams': 'libs/backbone/backbone.queryparams'
        },
        shim: {
            'backbone.queryparams': ['backbone']
        },
        packages: [
            {
                name: 'nls',
                location: 'dict'
            },
            {
                name: 'helpers',
                location: 'helpers'
            }
        ]
    },
    [
        'jquery',
        'backbone',
        'underscore',
        'routers/mainRouter',
        'helpers',
        'nls'
    ],
    function($, Backbone, _, router) {
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