define(function(require) {

    var router = require('router');

    return function() {

        var FirstStartResource = require('resources/firstStart/firstStart'),
            firstStartResource = new FirstStartResource;

        firstStartResource.fetch().then(function() {

            if (firstStartResource.data.complete) {
                router.navigate('dashboard');
            } else {
                router.navigate('firstStart');
            }
        })
    };
});