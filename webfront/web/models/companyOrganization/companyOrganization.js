define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model'),
        moment = require('moment'),
        _ = require('lodash');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/organizations',
        defaults: {
            legalDetails: {
                type: 'entrepreneur'
            }
        },
        saveData: function(){

            var legalDetails = _.extend({}, this.get('legalDetails'), {
                certificateDate: moment(this.get('legalDetails.certificateDate'), "DD.MM.YYYY").format('YYYY-MM-DD')
            });

            switch (legalDetails.type){
                case 'entrepreneur':
                    legalDetails = _.pick(legalDetails,
                        'type',
                        'fullName',
                        'legalAddress',
                        'inn',
                        'ogrnip',
                        'okpo',
                        'certificateNumber',
                        'certificateDate'
                    );
                    break;
                case 'legalEntity':
                    legalDetails = _.pick(legalDetails,
                        'type',
                        'fullName',
                        'legalAddress',
                        'inn',
                        'kpp',
                        'okpo',
                        'ogrn'
                    );
                    break;
            }

            return {
                name: this.get('name'),
                phone: this.get('phone'),
                fax: this.get('fax'),
                email: this.get('email'),
                director: this.get('director'),
                chiefAccountant: this.get('chiefAccountant'),
                legalDetails: legalDetails,
                address: this.get('address')
            }
        }
    });
});