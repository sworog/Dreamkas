define(function(require, exports, module) {
    //requirements
    var CompanyOrganizationModel = require('./companyOrganization');

    describe(module.id, function(){

        var companyOrganizationModel;

        beforeEach(function(){
            companyOrganizationModel = new CompanyOrganizationModel({

                name: 'name',
                phone: 'phone',
                fax: 'fax',
                email: 'email',
                director: 'director',
                chiefAccountant: 'chiefAccountant',
                address: 'address',

                legalDetails: {
                    type: 'type',
                    fullName: 'fullName',
                    legalAddress: 'legalAddress',
                    inn: 'inn',
                    ogrnip: 'ogrnip',
                    okpo: 'okpo',
                    certificateNumber: 'certificateNumber',
                    certificateDate: '10.11.2012',
                    anotherField: 'anotherField',
                    kpp: 'kpp',
                    ogrn: 'ogrn'
                },

                anotherField: 'anotherField'

            });
        });

        it('get entrepreneur data', function(){

            companyOrganizationModel.attributes.legalDetails.type = 'entrepreneur';

            expect(companyOrganizationModel.getData()).toEqual({

                name: 'name',
                phone: 'phone',
                fax: 'fax',
                email: 'email',
                director: 'director',
                chiefAccountant: 'chiefAccountant',
                address: 'address',

                legalDetails: {
                    type: 'entrepreneur',
                    fullName: 'fullName',
                    legalAddress: 'legalAddress',
                    inn: 'inn',
                    ogrnip: 'ogrnip',
                    okpo: 'okpo',
                    certificateNumber: 'certificateNumber',
                    certificateDate: '2012-11-10'
                }

            });

        });

        it('get legalEntity data', function(){

            companyOrganizationModel.attributes.legalDetails.type = 'legalEntity';

            expect(companyOrganizationModel.getData()).toEqual({

                name: 'name',
                phone: 'phone',
                fax: 'fax',
                email: 'email',
                director: 'director',
                chiefAccountant: 'chiefAccountant',
                address: 'address',

                legalDetails: {
                    type: 'legalEntity',
                    fullName: 'fullName',
                    legalAddress: 'legalAddress',
                    inn: 'inn',
                    okpo: 'okpo',
                    ogrn: 'ogrn',
                    kpp: 'kpp'
                }
            });

        });

    });
});