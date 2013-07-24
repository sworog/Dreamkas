define(function(require) {
        //requirements
        var Block = require('kit/block');

        return Block.extend({
            __name__: 'department',
            storeModel: null,
            departmentModel: null,
            templates: {
                index: require('tpl!blocks/department/templates/index.html')
            }
        })
    }
);
