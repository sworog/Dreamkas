define(function(require, exports, module) {
    //requirements
    var SupplierModel = require('./supplier');

    describe(module.id, function(){

        var supplierModel;

        beforeEach(function(){
            supplierModel = new SupplierModel({
                name: 'name',
                phone: 'phone',
                fax: 'fax',
                email: 'email',
                contactPerson: 'contactPerson',
                agreement: 'agreement',
                anotherField: 'anotherField'
            });
        });

        it('getData', function(){

            expect(supplierModel.getData()).toEqual({
                name: 'name',
                phone: 'phone',
                fax: 'fax',
                email: 'email',
                contactPerson: 'contactPerson',
                agreement: 'agreement'
            });

        });

    });
});