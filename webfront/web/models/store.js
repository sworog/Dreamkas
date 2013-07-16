define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel'),
            DepartmentsCollection = require('collections/departments');

        return BaseModel.extend({
            modelName: 'store',
            urlRoot: LH.baseApiUrl + '/stores',
            initData: {
                departments: DepartmentsCollection
            },
            saveFields: [
                'number',
                'address',
                'contacts'
            ]
        });
    }
);