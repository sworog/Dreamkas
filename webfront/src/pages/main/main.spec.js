define(function(require, exports, module) {
    //requirements
    var MainPage = require('./main');

    describe(module.id, function(){

        var mainPage;

        beforeEach(function(){
            mainPage = new MainPage;
        });

        it('заголовок страницы', function(){

            expect(mainPage.el.querySelector('.page__title').textContent).toBe('Обзор');

        });

        it('контент', function(){

            expect(mainPage.el.querySelector('.page__content h1').textContent).toBe('Добро пожаловать в Дримкас!');

        });

        it('активная ссылка в меню', function(){

            expect($.trim(mainPage.el.querySelector('.sideBar__item_active').textContent)).toBe('Обзор');

        });

    });

});