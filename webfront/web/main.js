require(
    {
        baseUrl: '/',
        paths: {
            'backbone.queryparams': 'libs/backbone/backbone.queryparams',
            'tpl': 'libs/require/tpl'
        },
        shim: {
            'backbone.queryparams': ['backbone']
        },
        packages: [
            {
                name: 'nls',
                location: 'dictionary'
            },
            {
                name: 'helpers',
                location: 'helpers'
            }
        ],
        config: {
            //Set the config for the i18n
            //module ID
            i18n: {
                locale: LH.locale === 'auto' ? undefined : LH.locale
            }
        }
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