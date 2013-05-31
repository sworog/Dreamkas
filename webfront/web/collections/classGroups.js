define(
    [
        './baseCollection.js',
        '/models/catalogGroup.js'
    ],
    function(BaseCollection, CatalogGroup) {
        return BaseCollection.extend({
            initialize: function(opt){
                this.classId = opt.classId;
            },
            model: CatalogGroup,
            url: function() {
                return baseApiUrl + '/klasses/'+ this.classId  + '/groups'
            }
        });
    }
);