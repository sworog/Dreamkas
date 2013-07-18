define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            DepartmentModel = require('models/department');

        return BaseCollection.extend({
            model: DepartmentModel,
            url: LH.baseApiUrl + '/departments',
            initialize: function(models, options){
                this.store = options.store || options.parentModel.id;
            }
        });
    }
);