window.baseApiUrl = "http://borovin.staging.api.lh.cs/api/1";

require.config({
    baseUrl: '/',
    paths: {
        tpl: '/libs/requirejs.tpl',
        text: '/libs/requirejs.text',
        json: '/libs/requirejs.json'
    },
    packages: [
        {
            name: 'baseApi',
            location: window.baseApiUrl
        }
    ]
});