define(function(require, exports, module) {
    //requirements
    var StoresPage = require('./stores');

    require('resources/store/mock/get/collection');

    describe(module.id, function(){

        var page;

        beforeEach(function(done){

            page = new StoresPage;

            page.on('loaded', function(){
                done();
            });

        });

        it('активный пункт меню - Магазины', function(){

            expect($.trim(page.$('.sideBar__item_active').text())).toBe('Магазины');

        });

    });

});