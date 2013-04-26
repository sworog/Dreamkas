define(function() {

    var keyCodes = {
        BACKSPACE: 8,
        ',': 188,
        '.': 190,
        DELETE: 46,
        DOWN: 40,
        END: 35,
        ENTER: 13,
        ESCAPE: 27,
        HOME: 36,
        LEFT: 37,
        NUMPAD_ADD: 107,
        NUMPAD_DECIMAL: 110,
        NUMPAD_DIVIDE: 111,
        NUMPAD_ENTER: 108,
        NUMPAD_MULTIPLY: 106,
        NUMPAD_SUBTRACT: 109,
        PAGE_DOWN: 34,
        PAGE_UP: 33,
        PERIOD: 190,
        RIGHT: 39,
        SPACE: 32,
        TAB: 9,
        UP: 38
    };

    var patterns = {
        price: /\d+[\.,]?\d{0,2}/
    };

    function fromCharCode(keyCode){
        var char = _.find(keyCodes, function(value, char){
            return keyCode == value;
        });
    }

    return function(e) {
        var $input = $(e.target),
            isValid = true,
            patternRegExp;

        var pattern = $(e.target).data('prevalidate');

        patternRegExp = new RegExp(pattern, 'ig');

        //console.log(patternRegExp.test($input.val() + String.fromCharCode(e.keyCode)));

        console.log(e.keyCode);

        if (_.indexOf(_.values(keyCodes), e.keyCode)) {
            return true;
        }
        if (e.ctrlKey || e.shiftKey) {
            return true;
        }

    }
});