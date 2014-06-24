define(function(require, exports, module) {
    //requirements
    var Page = require('./createCompanyOrganization'),
        $ = require('jquery');

    describe('page layout', function(){

        var page = new Page({
            el: document.createElement('div')
        });

        it('company global link', function(){
            expect(page.el.querySelector('.globalNavigation [href="/company"]').innerHTML).toEqual('Компания');
        });

        it('organizations local link', function(){
            expect(page.el.querySelector('.localNavigation [href="/company"]').innerHTML).toEqual('Организации');
        });

        it('add organization local link', function(){
            expect(page.el.querySelector('.localNavigation [href="/company/createOrganization"]').innerHTML).toEqual('Добавить организацию');
        });

        it('input name', function(){

            var $input = $('[name="name"][type="text"]', page.el);

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input phone', function(){

            var $input = $('[name="phone"][type="text"]', page.el);

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input fax', function(){

            var $input = $('[name="fax"][type="text"]', page.el);

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input email', function(){

            var $input = $('[name="email"][type="text"]', page.el);

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input director', function(){

            var $input = $('[name="director"][type="text"]', page.el);

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input chiefAccountant', function(){

            var $input = $('[name="chiefAccountant"][type="text"]', page.el);

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input address', function(){

            var $input = $('textarea[name="address"]', page.el);

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

    });
});