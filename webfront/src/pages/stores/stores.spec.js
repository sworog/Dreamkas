define(function(require, exports, module) {
    //requirements
    var Page = require('./stores');

    //mocks
    var ajaxMock = require('kit/ajaxMock/ajaxMock'),
        stores_3 = require('resources/store/mocks/1'),
        stores_0 = require('resources/store/mocks/2');

    describe(module.id, function(){

        beforeEach(function(){
            ajaxMock.clear();
        });

        it('активный пункт меню - Магазины', function(done){

            ajaxMock(stores_3);

            var page = new Page;

            page.on('loaded', function(){

                expect($.trim(page.$('.sideBar__item_active').text())).toBe('Магазины');

                done();
            });

        });

        it('сообщение, если магазинов нет', function(done){

            ajaxMock(stores_0);

            var page = new Page;

            page.on('loaded', function(){

                expect($.trim(page.$('.alert-info').text())).toBe('У вас ещё нет ни одного магазина');

                done();
            });

        });

    });

});