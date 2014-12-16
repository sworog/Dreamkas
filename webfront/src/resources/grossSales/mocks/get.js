define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/reports/grossMargin',
        type: 'GET',

        //response
        status: 200,
        responseText: {
            today: {
                now: {
                    date: "2013-11-25T16:37:25+0400",
                    value: 12345.45
                }
            },
            yesterday: {
                now: {
                    date: "2013-11-24T16:37:25+0400",
                    value: 45623.45,
                    diff: 5.12
                }
            },
            weekAgo: {
                now: {
                    date: "2014-12-05T16:00:25+0400",
                    value: 983478.45,
                    diff: -2.25
                }
            }
        }
    });
});