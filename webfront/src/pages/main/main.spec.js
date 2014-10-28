define(function(require, exports, module) {
    //requirements
    var MainPage = require('./main');

    describe(module.id, function(){

        var mainPage;

        beforeEach(function(){
            mainPage = new MainPage({
                el: '<div></div>'
            });
        });

        it('header title', function(){

            expect(mainPage.el.querySelector('.page__title').textContent).toBe('Обзор');

        });

        it('content title', function(){

            expect(mainPage.el.querySelector('.page__content h1').textContent).toBe('Добро пожаловать в Дримкас!');

        });

        it('active link in sidebar', function(){

            expect($.trim(mainPage.el.querySelector('.sideBar__item_active').textContent)).toBe('Обзор');

        });

    });

});