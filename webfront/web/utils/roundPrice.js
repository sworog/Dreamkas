define(function(require) {
    //requirements
    var cookie = require('cookies');

    return function(price, rounding){
        return $.ajax({
            url: LH.baseApiUrl + '/roundings/' + rounding + '/round',
            dataType: "json",
            type: 'POST',
            headers: {
                Authorization: 'Bearer ' + cookie.get('token')
            },
            data: {
                price: price
            }
        });
    }
});