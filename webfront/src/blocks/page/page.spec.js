define(function(require, exports, module) {
    //requirements
    var Page = require('./page');

    describe(module.id, function() {

        var page;

        beforeEach(function(){
            page = new Page;
        });

        it('global PAGE variable is right', function() {

            expect(window.PAGE).toBe(page);

        });

        it('page fetch method calls on init', function() {

            spyOn(Page.prototype, 'fetch');

            new Page;

            expect(Page.prototype.fetch).toHaveBeenCalled();

        });

        //--------------------------------- sidebar items --------------------------------------------

        it('1 ссылка в меню навигации - Обзор', function() {

            var item = page.$('.sideBar__item')[0];

            expect($.trim(item.textContent)).toBe('Обзор');
            expect(item.getAttribute('href')).toBe('/');

        });

        it('2 ссылка в меню навигации - Товародвижение', function() {

            var item = page.$('.sideBar__item')[1];

            expect($.trim(item.textContent)).toBe('Товародвижение');
            expect(item.getAttribute('href')).toBe('/stockMovements');

        });

        it('3 ссылка в меню навигации - Деньги', function() {

            var item = page.$('.sideBar__item')[2];

            expect($.trim(item.textContent)).toBe('Деньги');
            expect(item.getAttribute('href')).toBe('/cashFlow');

        });

        it('4 ссылка в меню навигации - Отчеты', function() {

            var item = page.$('.sideBar__item')[3];

            expect($.trim(item.textContent)).toBe('Отчеты');
            expect(item.getAttribute('href')).toBe('/reports');

        });

        it('5 ссылка в меню навигации - Ассортимент', function() {

            var item = page.$('.sideBar__item')[4];

            expect($.trim(item.textContent)).toBe('Ассортимент');
            expect(item.getAttribute('href')).toBe('/catalog');

        });

        it('6 ссылка в меню навигации - Поставщики', function() {

            var item = page.$('.sideBar__item')[5];

            expect($.trim(item.textContent)).toBe('Поставщики');
            expect(item.getAttribute('href')).toBe('/suppliers');

        });

        it('7 ссылка в меню навигации - Магазины', function() {

            var item = page.$('.sideBar__item')[6];

            expect($.trim(item.textContent)).toBe('Магазины');
            expect(item.getAttribute('href')).toBe('/stores');

        });
    });
});