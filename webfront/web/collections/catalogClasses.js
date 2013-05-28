define(
    [
        './baseCollection.js',
        '/models/catalogClass.js'
    ],
    function(BaseCollection, CatalogClass) {
        return BaseCollection.extend({
            model: CatalogClass,
            url: baseApiUrl + "/klasses.json"
        });
    }
);