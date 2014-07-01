define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model'),
        moment = require('moment');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/suppliers',
        defaults: {
            legalDetails: {
                type: 'entrepreneur'
            }
        },
        saveData: function() {
            var certificateDate = this.get('legalDetails.certificateDate') && moment(this.get('legalDetails.certificateDate'), "DD.MM.YYYY").format('YYYY-MM-DD');

            var legalDetails = _.extend({}, this.get('legalDetails'), {
                certificateDate: certificateDate
            });

            switch (legalDetails.type) {
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
                contactPerson: this.get('contactPerson'),
                agreement: this.get('agreement'),
                legalDetails: legalDetails
            }
        }
    });
});