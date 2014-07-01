define(function(require, exports, module) {
    //requirements
    var Page = require('./organization'),
        modelFixture = require('kit/modelFixture/modelFixture'),
        $ = require('jquery'),
        _ = require('lodash');

    var sharedSpecs = {
        globalNavigation: require('blocks/globalNavigation/globalNavigation.spec'),
        localNavigation_companyOrganization: require('blocks/localNavigation/localNavigation_companyOrganization.spec')
    };

    describe(module.id, function() {

        var page;

        beforeEach(function(done) {

            page = new Page({
                params: {
                    organizationId: _.uniqueId('organizationId')
                },
                models: {
                    companyOrganization: function() {
                        var page = this,
                            Model = modelFixture({
                                model: require('models/companyOrganization/companyOrganization'),
                                read: require('models/companyOrganization/fixtures/read')
                            });

                        return new Model({
                            id: page.params.organizationId
                        });
                    }
                }
            });

            page.on('loaded', function() {
                done();
            });

        });

        afterEach(function() {
            page.destroy();
        });

        sharedSpecs.globalNavigation();
        sharedSpecs.localNavigation_companyOrganization();

        it('input name', function() {
            expect($('[name="name"][type="text"]').val()).toEqual('name');
        });

        it('input phone', function() {
            expect($('[name="phone"][type="text"]').val()).toEqual('phone');
        });

        it('input fax', function() {
            expect($('[name="fax"][type="text"]').val()).toEqual('fax');
        });

        it('input email', function() {
            expect($('[name="email"][type="text"]').val()).toEqual('email');
        });

        it('input director', function() {
            expect($('[name="director"][type="text"]').val()).toEqual('director');
        });

        it('input chiefAccountant', function() {
            expect($('[name="chiefAccountant"][type="text"]').val()).toEqual('chiefAccountant');
        });

        it('input address', function() {
            expect($('textarea[name="address"]').val()).toEqual('address');
        });

        it('save button', function() {
            expect($.trim($('.form_companyOrganization button[type="submit"]').text())).toEqual('Сохранить');
        });

        it('form_organization block', function() {
            var Form_companyOrganization = require('blocks/form/form_companyOrganization/form_companyOrganization');

            expect(page.blocks.form_companyOrganization instanceof Form_companyOrganization).toBeTruthy();
        });


    });
});