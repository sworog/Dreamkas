define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/cashFlows',
        type: 'GET',

        //response
        status: 200,
        responseText: [
            {
                direction: 'in',
                date: "2014-11-24T11:10:56+0300",
                amount: 10000,
                comment: 'Нашел под кассой'
            },
            {
                direction: 'out',
                date: "2014-11-24T12:10:56+0300",
                amount: 23000,
                comment: 'Продажи'
            },
            {
                direction: 'out',
                date: "2014-11-24T13:10:56+0300",
                amount: 45340,
                comment: 'Списания'
            },
            {
                direction: 'out',
                date: "2014-11-24T11:10:56+0300",
                amount: 56450,
                comment: 'Аренда'
            },
            {
                direction: 'in',
                date: "2014-11-25T11:10:56+0300",
                amount: 10000,
                comment: 'Возврат от Валио'
            },
            {
                direction: 'out',
                date: "2014-11-26T11:10:56+0300",
                amount: 390,
                comment: 'Интернет'
            },
            {
                direction: 'out',
                date: "2014-11-28T11:10:56+0300",
                amount: 10000,
                comment: 'Взятка пожарным'
            },
            {
                direction: 'in',
                date: "2014-11-29T11:10:56+0300",
                amount: 459890,
                comment: 'Продажи'
            },
            {
                direction: 'out',
                date: "2014-11-29T13:10:56+0300",
                amount: 45340,
                comment: 'Списания'
            },
            {
                direction: 'in',
                date: "2014-11-30T11:10:56+0300",
                amount: 10000,
                comment: 'Нашел под кассой'
            }
        ]
    });
});