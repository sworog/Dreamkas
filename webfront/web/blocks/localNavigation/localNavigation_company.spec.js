define(function(require, exports, module) {
    //requirements
    var $ = require('jquery');

    return function(){
        describe(module.id, function(){

            it('organizations local link', function(){
                expect($('.localNavigation [href="/company"]').text()).toEqual('Организации');
            });

            it('add organization local link', function(){
                expect($('.localNavigation [href="/company/organizations/create"]').text()).toEqual('Добавить организацию');
            });

        });
    };
});