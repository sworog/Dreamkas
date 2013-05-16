require(
    {
        baseUrl: '/',
        paths: {
            jquery: '/libs/jquery/jquery.min',
            'jquery.maskedinput': '/libs/jquery/jquery.maskedinput',
            'jquery.initBlocks': '/libs/jquery/jquery.initBlocks',
            'jquery.mod': '/libs/jquery/jquery.mod',
            'jquery.ui': '/libs/jquery-ui/ui/minified/jquery-ui.min',

            underscore: '/libs/underscore/underscore.min',

            backbone: '/libs/backbone/backbone',
            'backbone.queryparams': '/libs/backbone/backbone.queryparams',
            'backbone.syphon': '/libs/backbone/backbone.syphon',

            moment: '/libs/moment/moment.min',
            'moment.ru': '/libs/moment/moment.ru',

            tpl: '/libs/require/tpl',

            lhAutocomplete: '/libs/lhAutocomplete'
        },
        shim: {
            'jquery.maskedinput': ['jquery'],
            'jquery.ui': ['jquery'],

            backbone: ['underscore'],
            'backbone.queryparams': ['backbone'],
            'backbone.syphon': ['backbone'],

            lhAutocomplete: ['jquery.ui']
        }
    },
    [
        '/helpers/helpers.js',
        '/routers/mainRouter.js',
        'jquery',
        'jquery.maskedinput',
        'jquery.initBlocks',
        'jquery.mod',
        'jquery.ui',
        'underscore',
        'backbone',
        'backbone.queryparams',
        'backbone.syphon',
        'moment',
        'moment.ru',
        'lhAutocomplete'
    ],
    function(helpers, router, $) {
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