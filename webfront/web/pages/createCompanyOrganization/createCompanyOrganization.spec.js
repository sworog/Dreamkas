define(function(require, exports, module) {
    //requirements
    var Page = require('./createCompanyOrganization'),
        $ = require('jquery');

    var sharedSpecs = {
        globalNavigation: require('blocks/globalNavigation/globalNavigation.spec'),
        localNavigation_company: require('blocks/localNavigation/localNavigation_company.spec')
    };

    describe(module.id, function(){

        var page;

        beforeEach(function(done){

            page = new Page();

            page.on('loaded', function(){
                done();
            });

        });

        afterEach(function(){
            page.destroy();
        });

        sharedSpecs.globalNavigation();
        sharedSpecs.localNavigation_company();

        it('input name', function(){

            var $input = $('[name="name"][type="text"]');

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input phone', function(){

            var $input = $('[name="phone"][type="text"]');

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input fax', function(){

            var $input = $('[name="fax"][type="text"]');

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input email', function(){

            var $input = $('[name="email"][type="text"]');

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input director', function(){

            var $input = $('[name="director"][type="text"]');

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input chiefAccountant', function(){

            var $input = $('[name="chiefAccountant"][type="text"]');

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('input address', function(){

            var $input = $('textarea[name="address"]');

            expect($input.length).toEqual(1);
            expect($input.val()).toEqual('');
        });

        it('add button', function(){

            var $button = $('.form_companyOrganization button[type="submit"]');

            expect($button.length).toEqual(1);
            expect($button.text()).toEqual('Добавить');
        });

        it('form_organization block', function(){
            var Form_companyOrganization = require('blocks/form/form_companyOrganization/form_companyOrganization');

            expect(page.blocks.form_companyOrganization instanceof Form_companyOrganization).toBeTruthy();
        });

    });
});