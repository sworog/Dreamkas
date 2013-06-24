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
                location: 'dictionary'
            },
            {
                name: 'utils',
                location: 'utils'
            },
            {
                name: 'routers',
                location: 'routers'
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
        'routers',
        'utils',
        'nls'
    ],
    function($, Backbone, _, routers) {
        $(function() {
            Backbone.history.start({
                pushState: true
            });

            $('body').on('click', '[href]', function(e) {
                e.preventDefault();
                routers.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });