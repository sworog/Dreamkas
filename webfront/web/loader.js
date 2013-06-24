require.config(
    {
        baseUrl: '/',
        paths: {
            'backbone.queryparams': 'libs/backbone/backbone.queryparams',
            'jquery.cookie': 'libs/jquery/jquery.cookie'
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
            }
        ],
        config: {
            //Set the config for the i18n
            //module ID
            i18n: {
                locale: LH.locale === 'auto' ? undefined : LH.locale
            }
        }
    });

require(['utils/checkToken'], function(checkToken) {
    checkToken().then(
        function(data) {
            console.log(data);
            require({
                paths: {
                    router: 'routers/' + data.status
                }
            }, [LH.mainJsFile]);
        },
        function(data) {
            console.log(data);
        });
});