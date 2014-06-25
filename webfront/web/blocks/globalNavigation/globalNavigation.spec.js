define(function(require, exports, module) {
    //requirements
    var $ = require('jquery');

    return function() {
        describe(module.id, function() {

            it('company global link', function() {
                expect($('.globalNavigation [href="/company"]').text()).toEqual('Компания');
            });

        });
    };
});