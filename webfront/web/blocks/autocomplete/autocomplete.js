define(function(require, exports, module) {
    //requirements
    var Block = require('block'),
        cookies = require('cookies');

    return Block.extend({
        cid: module.id,
        initialize: function(){
            var block = this;

            $(block.el).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: LH.baseApiUrl + block.el.dataset.url,
                        dataType: "json",
                        headers: {
                            Authorization: 'Bearer ' + cookies.get('token')
                        },
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item[name],
                                    product: item
                                }
                            }));
                        }
                    })
                },
                minLength: 3
            });
        }
    });
});