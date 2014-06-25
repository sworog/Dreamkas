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
            expect($('[name="name"][type="text"]').val()).toEqual('');
        });

        it('input phone', function(){
            expect($('[name="phone"][type="text"]').val()).toEqual('');
        });

        it('input fax', function(){
            expect($('[name="fax"][type="text"]').val()).toEqual('');
        });

        it('input email', function(){
            expect($('[name="email"][type="text"]').val()).toEqual('');
        });

        it('input director', function(){
            expect($('[name="director"][type="text"]').val()).toEqual('');
        });

        it('input chiefAccountant', function(){
            expect($('[name="chiefAccountant"][type="text"]').val()).toEqual('');
        });

        it('input address', function(){
            expect($('textarea[name="address"]').val()).toEqual('');
        });

        it('add button', function(){
            expect($.trim($('.form_companyOrganization button[type="submit"]').text())).toEqual('Добавить');
        });

        it('form_organization block', function(){
            var Form_companyOrganization = require('blocks/form/form_companyOrganization/form_companyOrganization');

            expect(page.blocks.form_companyOrganization instanceof Form_companyOrganization).toBeTruthy();
        });

    });
});