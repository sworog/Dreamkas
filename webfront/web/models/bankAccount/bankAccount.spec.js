define(function(require, exports, module) {
    //requirements
    var BankAccountModel = require('./bankAccount');

    describe(module.id, function(){

        var bankAccountModel;

        beforeEach(function(){
            bankAccountModel = new BankAccountModel({
                bic: 'bic',
                bankName: 'bankName',
                bankAddress: 'bankAddress',
                correspondentAccount: 'correspondentAccount',
                account: 'account',
                anotherField: 'anotherField'
            });
        });

        it('getData', function(){

            expect(bankAccountModel.getData()).toEqual({
                bic: 'bic',
                bankName: 'bankName',
                bankAddress: 'bankAddress',
                correspondentAccount: 'correspondentAccount',
                account: 'account'
            });

        });

    });
});