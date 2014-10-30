define(function(require, exports, module) {
    //requirements
    var Page = require('./page');

    describe(module.id, function() {

        var page;

        beforeEach(function(){
            page = new Page({
                el: '<div></div>'
            });
        });

        it('global PAGE variable is right', function() {

            expect(window.PAGE).toBe(page);

        });

        it('content of #page-wrapper is right', function() {

            expect($('.page__contentSide', page.el).html().trim()).toBe(page.content());

        });

        it('page fetch', function() {

            spyOn(Page.prototype, 'fetch');

            var page = new Page({
                el: '<div></div>'
            });

            expect(Page.prototype.fetch).toHaveBeenCalled();

        });

        it('первая ссылка в меню навигации - Обзор', function() {

            expect($.trim(page.el.querySelectorAll('.sideBar__item')[0].textContent)).toBe('Обзор');

        });

        it('вторая ссылка в меню навигации - Товародвижение', function() {

            expect($.trim(page.el.querySelectorAll('.sideBar__item')[1].textContent)).toBe('Товародвижение');

        });

        it('третья ссылка в меню навигации - Отчеты', function() {

            expect($.trim(page.el.querySelectorAll('.sideBar__item')[2].textContent)).toBe('Отчеты');

        });

        it('четвертая ссылка в меню навигации - Ассортимент', function() {

            expect($.trim(page.el.querySelectorAll('.sideBar__item')[3].textContent)).toBe('Ассортимент');

        });

        it('пятая ссылка в меню навигации - Поставщики', function() {

            expect($.trim(page.el.querySelectorAll('.sideBar__item')[4].textContent)).toBe('Поставщики');

        });

        it('шестая ссылка в меню навигации - Магазины', function() {

            expect($.trim(page.el.querySelectorAll('.sideBar__item')[5].textContent)).toBe('Магазины');

        });
    });
});