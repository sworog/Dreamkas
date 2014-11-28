define(function(require, exports, module) {
    //requirements
    var Page = require('./stores');

    //mocks
    var ajaxMock = require('kit/ajaxMock/ajaxMock'),
        stores_3 = require('resources/store/mocks/1'),
        stores_0 = require('resources/store/mocks/2');

    describe(module.id, function(){

        describe('3 stores', function(){
            var page;

            beforeEach(function(done){

                ajaxMock.clear();

                ajaxMock(stores_3);

                page = new Page;

                page.on('loaded', function(){
                    done();
                });
            });

            it('активный пункт меню - Магазины', function(){

                expect($.trim(page.$('.sideBar__item_active').text())).toBe('Магазины');

            });
        });

        describe('0 stores', function(){
            var page;

            beforeEach(function(done){

                ajaxMock.clear();

                ajaxMock(stores_0);

                page = new Page;

                page.on('loaded', function(){
                    done();
                });
            });

            it('Сообщение, если магазинов нет', function(){

                expect($.trim(page.$('.alert-info').text())).toBe('У вас ещё нет ни одного магазина');

            });
        });
    });

});